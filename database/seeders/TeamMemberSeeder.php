<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = [
            [
                'name' => 'Olamide Johnson Isaiah',
                'role' => 'CEO / Founder',
                'bio' => 'Visionary technology leader driving digital transformation, enterprise software strategy, and strategic growth at Skynet Digital Limited.',
                'initials' => 'OJI',
                'gradient' => 'from-primary to-indigo-600',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Temitope Joseph Orubuloye',
                'role' => 'Graphic Designer / Frontend Specialist',
                'bio' => 'Bringing creative brand designs to life with pixel-perfect frontend development and responsive web engineering.',
                'initials' => 'TJO',
                'gradient' => 'from-indigo-600 to-purple-600',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Emmanuel Monday Ekere',
                'role' => 'Senior UI/UX Designer',
                'bio' => 'Crafting intuitive, human-centered user experiences and high-conversion design systems across web & mobile applications.',
                'initials' => 'EME',
                'gradient' => 'from-purple-600 to-pink-600',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($members as $data) {
            TeamMember::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}
