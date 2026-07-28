<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectMedia;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projectsData = [
            [
                'title' => 'FinTech PayGate Core Platform',
                'industry' => 'Fintech',
                'service' => 'Backend & API Development',
                'category' => 'SaaS Platform',
                'description' => "FinTech PayGate is an enterprise-grade payment gateway architecture capable of processing high-volume real-time multi-currency transactions with ISO-20022 compliance and AI-driven fraud detection analytics.\n\n### Key Features\n- Real-time transaction processing & settlement\n- Sub-second API response times\n- PCI-DSS Level 1 certified security architecture\n- Comprehensive reporting dashboard for merchants",
                'demo_url' => 'https://paygate.skynetdigital.example',
                'is_published' => true,
                'media' => [
                    ['type' => 'image', 'url' => 'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?w=1200&q=80'],
                    ['type' => 'image', 'url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=1200&q=80'],
                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
                ]
            ],
            [
                'title' => 'HealthPulse Telehealth & EHR System',
                'industry' => 'Healthcare',
                'service' => 'Full-Stack Development',
                'category' => 'Enterprise System',
                'description' => "HealthPulse is a HIPAA-compliant Electronic Health Records (EHR) and video consultation platform connecting medical specialists with patients across web & mobile devices.\n\n### Highlights\n- Encrypted WebRTC HD video consultation\n- Automated appointment scheduling & calendar sync\n- Digital prescription & pharmacy integration\n- Secure cloud medical records vault",
                'demo_url' => 'https://healthpulse.skynetdigital.example',
                'is_published' => true,
                'media' => [
                    ['type' => 'image', 'url' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=1200&q=80'],
                    ['type' => 'image', 'url' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=1200&q=80'],
                ]
            ],
            [
                'title' => 'OmniMart Headless E-Commerce Ecosystem',
                'industry' => 'E-Commerce',
                'service' => 'Web Application / Web Development',
                'category' => 'E-Commerce & Retail Portal',
                'description' => "High-scalability headless storefront built with Next.js, Laravel API, and Stripe for seamless international checkout, dynamic localization, and multi-warehouse inventory syncing.",
                'demo_url' => 'https://omnimart.skynetdigital.example',
                'is_published' => true,
                'media' => [
                    ['type' => 'image', 'url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1200&q=80'],
                    ['type' => 'image', 'url' => 'https://images.unsplash.com/photo-1556742049-0a670f4a4591?w=1200&q=80'],
                ]
            ],
            [
                'title' => 'Nova AI Real-Time Predictive Analytics',
                'industry' => 'Artificial Intelligence',
                'service' => 'Frontend Development',
                'category' => 'SaaS Platform',
                'description' => "Next-gen executive analytics suite featuring real-time data streaming WebSocket pipelines, interactive canvas charts, and predictive machine learning forecasting models.",
                'demo_url' => 'https://nova-ai.skynetdigital.example',
                'is_published' => true,
                'media' => [
                    ['type' => 'image', 'url' => 'https://images.unsplash.com/photo-1504868584819-f8e8b4b6d7e3?w=1200&q=80'],
                    ['type' => 'image', 'url' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=1200&q=80'],
                ]
            ],
            [
                'title' => 'UrbanShield Mobile Security Suite',
                'industry' => 'Cybersecurity',
                'service' => 'Mobile App Development',
                'category' => 'Mobile Application (iOS / Android)',
                'description' => "Cross-platform mobile security suite providing end-to-end encrypted messaging, automated threat detection, biometric vault locks, and network vulnerability alerts.",
                'demo_url' => 'https://urbanshield.skynetdigital.example',
                'is_published' => true,
                'media' => [
                    ['type' => 'image', 'url' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?w=1200&q=80'],
                    ['type' => 'image', 'url' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=1200&q=80'],
                ]
            ],
            [
                'title' => 'Apex Global Brand Design System',
                'industry' => 'Corporate',
                'service' => 'UI/UX Design',
                'category' => 'Brand Identity & Design System',
                'description' => "Comprehensive design system, accessible UI component library, and digital brand identity guidelines created for international financial institution Apex Group.",
                'demo_url' => 'https://apex-design.skynetdigital.example',
                'is_published' => true,
                'media' => [
                    ['type' => 'image', 'url' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?w=1200&q=80'],
                ]
            ],
            [
                'title' => 'CloudOps Multi-Region Kubernetes Cluster',
                'industry' => 'Cloud Infrastructure',
                'service' => 'Cloud Infrastructure & DevOps',
                'category' => 'Custom Software / Microservices',
                'description' => "Automated Kubernetes CI/CD deployment infrastructure spanning AWS and GCP with automated failover, Terraform infrastructure-as-code, and zero-downtime deployments.",
                'demo_url' => 'https://cloudops.skynetdigital.example',
                'is_published' => false, // Draft project to test draft filter!
                'media' => [
                    ['type' => 'image', 'url' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=1200&q=80'],
                ]
            ],
            [
                'title' => 'EduSmart Interactive Learning Management',
                'industry' => 'Education',
                'service' => 'Web Application / Web Development',
                'category' => 'Corporate / Institutional Website',
                'description' => "Interactive learning management portal supporting video streaming, automated grading, live quiz engines, certificate generation, and student performance tracking.",
                'demo_url' => 'https://edusmart.skynetdigital.example',
                'is_published' => true,
                'media' => [
                    ['type' => 'image', 'url' => 'https://images.unsplash.com/photo-1501504905252-473c47e087f8?w=1200&q=80'],
                ]
            ],
        ];

        foreach ($projectsData as $pData) {
            $mediaItems = $pData['media'] ?? [];
            unset($pData['media']);

            $project = Project::create($pData);

            foreach ($mediaItems as $idx => $m) {
                ProjectMedia::create([
                    'project_id' => $project->id,
                    'type' => $m['type'],
                    'source_type' => 'url',
                    'url' => $m['url'],
                    'order' => $idx,
                ]);
            }
        }
    }
}
