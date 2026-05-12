<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Package;
use App\Models\ShipRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminClientSeeder extends Seeder
{
    public function run(): void
    {
        Storage::disk('public')->put(
            'invoices/sample-invoice.pdf',
            "%PDF-1.1\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Count 1/Kids[3 0 R]>>endobj\n3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 300 144]/Contents 4 0 R>>endobj\n4 0 obj<</Length 46>>stream\nBT /F1 12 Tf 30 100 Td (Ship2Aruba Sample Invoice) Tj ET\nendstream endobj\nxref\n0 5\n0000000000 65535 f\n0000000010 00000 n\n0000000060 00000 n\n0000000117 00000 n\n0000000215 00000 n\ntrailer<</Size 5/Root 1 0 R>>\nstartxref\n318\n%%EOF"
        );

        $admin = User::updateOrCreate(
            ['email' => 'admin@ship2aruba.test'],
            [
                'name' => 'Robert Anderson',
                'role' => 'admin',
                'suite_number' => null,
                'password' => Hash::make('password'),
            ]
        );

        $clients = [
            ['name' => 'Michael Thompson', 'email' => 'michael.thompson@example.com', 'suite' => 'S2A-1001'],
            ['name' => 'Sarah Williams', 'email' => 'sarah.williams@example.com', 'suite' => 'S2A-1002'],
            ['name' => 'James Rodriguez', 'email' => 'james.rodriguez@example.com', 'suite' => 'S2A-1003'],
            ['name' => 'Jessica Davis', 'email' => 'jessica.davis@example.com', 'suite' => 'S2A-1004'],
            ['name' => 'Christopher Lee', 'email' => 'christopher.lee@example.com', 'suite' => 'S2A-1005'],
            ['name' => 'Amanda Johnson', 'email' => 'amanda.johnson@example.com', 'suite' => 'S2A-1006'],
            ['name' => 'David Martinez', 'email' => 'david.martinez@example.com', 'suite' => 'S2A-1007'],
            ['name' => 'Emily Brown', 'email' => 'emily.brown@example.com', 'suite' => 'S2A-1008'],
        ];

        $clientModels = [];
        foreach ($clients as $c) {
            $clientModels[] = User::updateOrCreate(
                ['email' => $c['email']],
                [
                    'name' => $c['name'],
                    'role' => 'client',
                    'suite_number' => $c['suite'],
                    'password' => Hash::make('password'),
                ]
            );
        }

        $primaryClient = User::updateOrCreate(
            ['email' => 'client@ship2aruba.test'],
            [
                'name' => 'Demo Client',
                'role' => 'client',
                'suite_number' => 'S2A-1000',
                'password' => Hash::make('password'),
            ]
        );
        array_unshift($clientModels, $primaryClient);

        $packageBlueprints = [
            ['desc' => 'Apple AirPods Pro (2nd generation)', 'w' => 6, 'h' => 5, 'l' => 8, 'kg' => 0.5, 'status' => Package::STATUS_READY_TO_SEND],
            ['desc' => 'Amazon Kindle Paperwhite e-reader', 'w' => 18, 'h' => 12, 'l' => 3, 'kg' => 0.6, 'status' => Package::STATUS_READY_TO_SEND],
            ['desc' => 'Nike Air Max 270 sneakers, size 10', 'w' => 33, 'h' => 22, 'l' => 14, 'kg' => 1.2, 'status' => Package::STATUS_PENDING_INVOICE_REVIEW],
            ['desc' => 'Levi\'s 511 slim fit jeans, dark wash', 'w' => 30, 'h' => 22, 'l' => 6, 'kg' => 0.8, 'status' => Package::STATUS_PENDING_INVOICE_REVIEW],
            ['desc' => 'KitchenAid Stand Mixer 5-quart', 'w' => 40, 'h' => 36, 'l' => 26, 'kg' => 10.5, 'status' => Package::STATUS_NEEDS_REVIEW, 'note' => 'Invoice image is blurry — please re-upload a clear scan.'],
            ['desc' => 'Bose QuietComfort 45 headphones', 'w' => 22, 'h' => 20, 'l' => 9, 'kg' => 1.1, 'status' => Package::STATUS_INVOICE_APPROVED],
            ['desc' => 'Sony PlayStation 5 console (disc edition)', 'w' => 42, 'h' => 26, 'l' => 12, 'kg' => 4.5, 'status' => Package::STATUS_INVOICE_APPROVED],
            ['desc' => 'Patagonia Nano Puff jacket, men\'s medium', 'w' => 32, 'h' => 24, 'l' => 8, 'kg' => 0.9, 'status' => Package::STATUS_INVOICE_APPROVED],
            ['desc' => 'Dyson V12 Detect Slim cordless vacuum', 'w' => 30, 'h' => 28, 'l' => 12, 'kg' => 3.2, 'status' => Package::STATUS_SHIP_REQUESTED],
            ['desc' => 'Apple MacBook Air 13" M3 silver', 'w' => 34, 'h' => 24, 'l' => 4, 'kg' => 1.3, 'status' => Package::STATUS_SHIP_REQUESTED],
            ['desc' => 'Lego Star Wars Millennium Falcon set', 'w' => 60, 'h' => 38, 'l' => 12, 'kg' => 4.0, 'status' => Package::STATUS_SHIPPED],
            ['desc' => 'Instant Pot Duo 7-in-1, 6-quart', 'w' => 35, 'h' => 34, 'l' => 33, 'kg' => 5.6, 'status' => Package::STATUS_SHIPPED],
            ['desc' => 'Ralph Lauren Polo shirt set (3-pack)', 'w' => 28, 'h' => 22, 'l' => 4, 'kg' => 0.7, 'status' => Package::STATUS_READY_FOR_PICKUP],
            ['desc' => 'Garmin Forerunner 265 GPS watch', 'w' => 12, 'h' => 12, 'l' => 6, 'kg' => 0.3, 'status' => Package::STATUS_READY_FOR_PICKUP],
            ['desc' => 'iRobot Roomba i7+ robot vacuum', 'w' => 40, 'h' => 40, 'l' => 12, 'kg' => 4.2, 'status' => Package::STATUS_DELIVERED],
            ['desc' => 'GoPro HERO12 Black action camera', 'w' => 14, 'h' => 12, 'l' => 8, 'kg' => 0.4, 'status' => Package::STATUS_DELIVERED],
            ['desc' => 'Stanley Quencher H2.0 40oz tumbler (pack of 2)', 'w' => 24, 'h' => 24, 'l' => 12, 'kg' => 1.2, 'status' => Package::STATUS_READY_TO_SEND],
            ['desc' => 'Anker PowerCore 26800 portable charger', 'w' => 18, 'h' => 8, 'l' => 4, 'kg' => 0.6, 'status' => Package::STATUS_INVOICE_APPROVED],
        ];

        $packages = [];
        foreach ($packageBlueprints as $index => $bp) {
            $client = $clientModels[$index % count($clientModels)];
            $trackingNumber = sprintf('S2A-PKG-%04d', 1001 + $index);

            $package = Package::updateOrCreate(
                ['tracking_number' => $trackingNumber],
                [
                    'client_id' => $client->id,
                    'width' => $bp['w'],
                    'height' => $bp['h'],
                    'length' => $bp['l'],
                    'weight' => $bp['kg'],
                    'contents_description' => $bp['desc'],
                    'status' => $bp['status'],
                    'received_at' => now()->subDays(rand(1, 28))->subHours(rand(0, 23)),
                ]
            );

            $package->statusHistory()->delete();
            $this->seedHistory($package, $admin);

            if (in_array($bp['status'], [
                Package::STATUS_PENDING_INVOICE_REVIEW,
                Package::STATUS_NEEDS_REVIEW,
                Package::STATUS_INVOICE_APPROVED,
                Package::STATUS_SHIP_REQUESTED,
                Package::STATUS_SHIPPED,
                Package::STATUS_READY_FOR_PICKUP,
                Package::STATUS_DELIVERED,
            ], true)) {
                $reviewStatus = match ($bp['status']) {
                    Package::STATUS_PENDING_INVOICE_REVIEW => 'pending',
                    Package::STATUS_NEEDS_REVIEW => 'needs_review',
                    default => 'approved',
                };
                Invoice::updateOrCreate(
                    ['package_id' => $package->id],
                    [
                        'file_path' => 'invoices/sample-invoice.pdf',
                        'review_status' => $reviewStatus,
                        'admin_note' => $bp['note'] ?? null,
                        'reviewed_by' => $reviewStatus === 'pending' ? null : $admin->id,
                        'reviewed_at' => $reviewStatus === 'pending' ? null : $package->received_at->copy()->addHours(rand(2, 24)),
                    ]
                );
            }

            $packages[] = $package;
        }

        $this->seedShipRequests($packages, $admin);
    }

    protected function seedHistory(Package $package, User $admin): void
    {
        $flow = [
            Package::STATUS_READY_TO_SEND,
            Package::STATUS_PENDING_INVOICE_REVIEW,
            Package::STATUS_INVOICE_APPROVED,
            Package::STATUS_SHIP_REQUESTED,
            Package::STATUS_SHIPPED,
            Package::STATUS_READY_FOR_PICKUP,
            Package::STATUS_DELIVERED,
        ];

        if ($package->status === Package::STATUS_NEEDS_REVIEW) {
            $sequence = [Package::STATUS_READY_TO_SEND, Package::STATUS_PENDING_INVOICE_REVIEW, Package::STATUS_NEEDS_REVIEW];
        } else {
            $finalIndex = array_search($package->status, $flow, true);
            $sequence = $finalIndex === false ? [Package::STATUS_READY_TO_SEND] : array_slice($flow, 0, $finalIndex + 1);
        }

        $previous = null;
        $base = $package->received_at ? $package->received_at->copy() : now()->subDays(7);
        foreach ($sequence as $i => $status) {
            $package->statusHistory()->create([
                'old_status' => $previous,
                'new_status' => $status,
                'changed_by' => $i === 0 ? $admin->id : ($i % 2 === 0 ? $admin->id : $package->client_id),
                'changed_at' => $base->copy()->addHours($i * rand(4, 18)),
            ]);
            $previous = $status;
        }
    }

    protected function seedShipRequests(array $packages, User $admin): void
    {
        $byStatus = collect($packages)->groupBy('status');

        $shipRequestedPackages = $byStatus[Package::STATUS_SHIP_REQUESTED] ?? collect();
        if ($shipRequestedPackages->isNotEmpty()) {
            foreach ($shipRequestedPackages->groupBy('client_id') as $clientId => $clientPackages) {
                $sr = ShipRequest::create([
                    'client_id' => $clientId,
                    'status' => 'submitted',
                    'submitted_at' => now()->subDays(rand(1, 3)),
                ]);
                $sr->packages()->attach($clientPackages->pluck('id'));
            }
        }

        $shippedPackages = $byStatus[Package::STATUS_SHIPPED] ?? collect();
        $readyPackages = $byStatus[Package::STATUS_READY_FOR_PICKUP] ?? collect();
        $deliveredPackages = $byStatus[Package::STATUS_DELIVERED] ?? collect();
        $processedPool = $shippedPackages->concat($readyPackages)->concat($deliveredPackages);

        if ($processedPool->isNotEmpty()) {
            foreach ($processedPool->groupBy('client_id') as $clientId => $clientPackages) {
                $sr = ShipRequest::create([
                    'client_id' => $clientId,
                    'status' => 'processed',
                    'submitted_at' => now()->subDays(rand(5, 14)),
                    'processed_by' => $admin->id,
                    'processed_at' => now()->subDays(rand(2, 10)),
                ]);
                $sr->packages()->attach($clientPackages->pluck('id'));
            }
        }
    }
}
