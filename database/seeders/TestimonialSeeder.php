<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Dr. Emmanuel Nwachukwu',
                'email' => 'nwachukwu@apexhealth.ng',
                'company' => 'Director, Apex Health Systems',
                'rating' => 5,
                'message' => 'Skynet Digital delivered our hospital management and tele-health infrastructure ahead of schedule. Their enterprise software engineering and network security are world-class.',
                'is_approved' => true,
            ],
            [
                'name' => 'Mrs. Florence Adebayo',
                'email' => 'adebayo@nexuslogistics.com',
                'company' => 'COO, Nexus Global Logistics',
                'rating' => 5,
                'message' => 'Partnering with Skynet Digital for our real-time fleet tracking and API integrations transformed our operations. Professionalism and 24/7 technical support at its best!',
                'is_approved' => true,
            ],
            [
                'name' => 'Engr. Kenneth Ebi',
                'email' => 'k.ebi@deltaoilfield.com',
                'company' => 'Head of IT, Delta Oilfield Services',
                'rating' => 5,
                'message' => 'Outstanding enterprise networking setup and IT infrastructure maintenance. Skynet Digital Limited is our most trusted technology partner in Nigeria.',
                'is_approved' => true,
            ],
        ];

        foreach ($testimonials as $data) {
            Testimonial::updateOrCreate(
                ['email' => $data['email']],
                $data
            );
        }
    }
}
