<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationSetting;
use App\Services\ApplicationSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = ApplicationSetting::query()
            ->orderBy('group')
            ->orderBy('key')
            ->get()
            ->groupBy(function (ApplicationSetting $s) {
                return $s->group ?: 'general';
            });

        /** @var \App\Models\User $user */
        $user = Auth::user();

        return view('admin.settings.index', compact('settings', 'user'));
    }

    public function updateApp(Request $request)
    {
        $payload = $request->input('settings', []);

        $settings = ApplicationSetting::query()->get();

        $rules = [];
        foreach ($settings as $setting) {
            $field = 'settings.' . $setting->key;
            $type = (string) $setting->type;

            $rules[$field] = match ($type) {
                'bool' => ['nullable', 'in:0,1'],
                'int' => ['nullable', 'integer'],
                'float' => ['nullable', 'numeric'],
                'json' => [
                    'nullable',
                    function ($attribute, $value, $fail) {
                        if ($value === null) {
                            return;
                        }
                        $str = trim((string) $value);
                        if ($str === '') {
                            return;
                        }
                        json_decode($str, true);
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            $fail('Invalid JSON.');
                        }
                    },
                ],
                default => ['nullable', 'string', 'max:65535'],
            };
        }

        $request->validate($rules);

        DB::transaction(function () use ($settings, $payload) {
            foreach ($settings as $setting) {
                $key = (string) $setting->key;

                if (!array_key_exists($key, $payload)) {
                    // Don't change missing keys.
                    continue;
                }

                $raw = $payload[$key];
                $setting->value = $this->normalizeSettingValue($raw, (string) $setting->type);
                $setting->save();
            }
        });

        ApplicationSettingsService::clearCache();

        return back()->with('success', 'App settings updated.');
    }

    public function updateAccountProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        $user->update($validated);

        return back()->with('success', 'Account profile updated.');
    }

    public function updateAccountPassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated.');
    }

    private function normalizeSettingValue($raw, string $type): ?string
    {
        if ($raw === null) {
            return null;
        }

        // Everything is stored as text in DB. We normalize based on type.
        return match ($type) {
            'bool' => (filter_var($raw, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false) ? '1' : '0',
            'int' => (string) (int) $raw,
            'float' => (string) (float) $raw,
            'json' => $this->normalizeJson($raw),
            default => (string) $raw,
        };
    }

    private function normalizeJson($raw): string
    {
        if (is_array($raw)) {
            return json_encode($raw, JSON_UNESCAPED_SLASHES);
        }

        $rawString = trim((string) $raw);
        if ($rawString === '') {
            return '[]';
        }

        $decoded = json_decode($rawString, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // If invalid JSON, keep original string to avoid data loss.
            return $rawString;
        }

        return json_encode($decoded, JSON_UNESCAPED_SLASHES);
    }
}

