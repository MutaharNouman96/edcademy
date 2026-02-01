<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'How to Choose the Right Online Course for Your Child',
                'author' => 'Ed‑Cademy Team',
                'status' => 'published',
                'tags' => 'parenting, learning, tips',
                'content' => '<p>Choosing the right course is easier when you start with goals, age, and learning style.</p>
<h3>1) Start with the outcome</h3>
<p>What should your child be able to do after finishing?</p>
<h3>2) Check instructor credibility</h3>
<p>Look for expertise, reviews, and sample lessons.</p>',
            ],
            [
                'title' => 'Study Habits That Actually Work (Backed by Science)',
                'author' => 'Ed‑Cademy Team',
                'status' => 'published',
                'tags' => 'productivity, study, students',
                'content' => '<p>Spaced repetition, retrieval practice, and interleaving are three strategies that consistently win.</p>
<ul>
  <li>Use short daily sessions</li>
  <li>Quiz yourself</li>
  <li>Mix topics</li>
</ul>',
            ],
            [
                'title' => 'Educator Guide: Planning a Great Video Lesson',
                'author' => 'Ed‑Cademy Team',
                'status' => 'published',
                'tags' => 'educators, video, teaching',
                'content' => '<p>A great lesson has one core idea, clear examples, and a short practice segment.</p>
<p>Keep videos 6–12 minutes where possible and split longer topics into chapters.</p>',
            ],
            [
                'title' => 'Safety & Trust: How We Keep Learning Secure',
                'author' => 'Ed‑Cademy Team',
                'status' => 'published',
                'tags' => 'safety, trust, policy',
                'content' => '<p>We combine verification, moderation, and privacy controls to keep learners protected.</p>',
            ],
            [
                'title' => 'Draft: Upcoming Features (Internal)',
                'author' => 'Ed‑Cademy Team',
                'status' => 'draft',
                'tags' => 'roadmap',
                'content' => '<p>This is a draft post and should not appear publicly.</p>',
            ],
        ];

        foreach ($posts as $post) {
            Blog::query()->firstOrCreate(
                ['title' => $post['title']],
                $post
            );
        }
    }
}

