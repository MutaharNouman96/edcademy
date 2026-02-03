<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $policies = [
            [
                'name' => 'Educator Policy',
                'slug' => 'educator-policy',
                'content' => '
                    <h2>Educator Code of Conduct</h2>
                    <ul>
                        <li>All educators must uphold professional standards and deliver accurate course information.</li>
                        <li>Respectful and inclusive language is required in all interactions.</li>
                        <li>Materials must not infringe on copyrights or intellectual property of others.</li>
                    </ul>
                    <h3>Expectations</h3>
                    <ol>
                        <li>Create and publish course content that benefits students.</li>
                        <li>Respond to student inquiries in a timely and respectful manner.</li>
                        <li>Comply with all platform and legal requirements.</li>
                    </ol>
                ',
            ],
            [
                'name' => 'Student Policy',
                'slug' => 'student-policy',
                'content' => '
                    <h2>Student Guidelines</h2>
                    <ul>
                        <li>Students must use respectful communication at all times.</li>
                        <li>Cheating, plagiarism, or any dishonest behavior is strictly prohibited.</li>
                        <li>Your account is personal – sharing login credentials is not allowed.</li>
                    </ul>
                    <h3>Support</h3>
                    <p>If you need help, reach out to support through the contact page.</p>
                ',
            ],
            [
                'name' => 'Refund Policy',
                'slug' => 'refund-policy',
                'content' => '
                    <h2>Refund Policy</h2>
                    <p>We want you to be satisfied with your purchase. If you are not, please review our refund eligibility below:</p>
                    <ul>
                        <li>Requests must be made within 14 days of purchase.</li>
                        <li>Course progress must not exceed 25% completion to qualify for a refund.</li>
                        <li>To request a refund, email <a href="mailto:support@example.com">support@example.com</a> with your order details.</li>
                    </ul>
                    <p>Refunds are typically processed within 5-7 business days.</p>
                ',
            ],
            [
                'name' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '
                    <h2>Privacy Policy</h2>
                    <p>Your privacy is important to us. We collect necessary personal information to provide services and improve user experience.</p>
                    <ul>
                        <li>We do not sell or share your personal data with third parties without consent.</li>
                        <li>All transactions are secured using industry-standard encryption.</li>
                    </ul>
                    <h3>Data Retention</h3>
                    <p>Information is retained as long as your account is active or as required by law.</p>
                ',
            ],
            [
                'name' => 'Terms & Conditions',
                'slug' => 'terms-and-conditions',
                'content' => '
                    <h2>Terms &amp; Conditions</h2>
                    <ol>
                        <li>By using this website, you agree to comply with all site rules.</li>
                        <li>Content may not be republished or redistributed without permission.</li>
                        <li>We may update these terms at any time. Continued use of the site denotes acceptance of updated terms.</li>
                    </ol>
                    <h3>Disclaimer</h3>
                    <p>All courses and content are provided "as-is" and without warranty.</p>
                ',
            ],
        ];

        foreach ($policies as $policy) {
            \App\Models\Policy::updateOrCreate(
                ['slug' => $policy['slug']],
                [
                    'name' => $policy['name'],
                    'content' => $policy['content'],
                ]
            );
        }
    }
}
