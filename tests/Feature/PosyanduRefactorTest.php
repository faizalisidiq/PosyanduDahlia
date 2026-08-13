<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Staff;
use App\Models\HealthPost;
use App\Models\Mother;
use App\Models\Children;
use App\Models\Elderly;
use App\Models\PregnancyRecord;
use App\Models\ChildbirthRecord;
use App\Models\GrowthMonitoring;
use App\Models\IlpScreening;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PosyanduRefactorTest extends TestCase
{
    use RefreshDatabase;

    protected HealthPost $healthPost;
    protected Staff $adminStaff;
    protected Staff $kaderStaff;
    protected User $adminUser;
    protected User $kaderUser;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create a Health Post
        $this->healthPost = HealthPost::create([
            'name' => 'Posyandu Mawar',
            'address' => 'Jl. Mawar Indah',
            'phone' => '081234567890',
        ]);

        // 2. Create Admin (Ketua Kader) User
        $this->adminUser = User::create([
            'name' => 'Ketua Kader User',
            'email' => 'ketua@posyandu.id',
            'password' => bcrypt('password'),
        ]);

        $this->adminStaff = Staff::create([
            'user_id' => $this->adminUser->id,
            'health_post_id' => $this->healthPost->id,
            'role' => 'ketua-kader',
            'phone' => '08121111111',
            'address' => 'Alamat Ketua',
            'status' => 'active',
        ]);

        // 3. Create Kader (Anggota Kader) User
        $this->kaderUser = User::create([
            'name' => 'Kader Anggota User',
            'email' => 'kader@posyandu.id',
            'password' => bcrypt('password'),
        ]);

        $this->kaderStaff = Staff::create([
            'user_id' => $this->kaderUser->id,
            'health_post_id' => $this->healthPost->id,
            'role' => 'anggota-kader',
            'phone' => '08122222222',
            'address' => 'Alamat Anggota',
            'status' => 'active',
        ]);
    }

    protected function createTestMother(array $override = []): Mother
    {
        return Mother::create(array_merge([
            'name' => 'Ibu Maria',
            'identity_number' => '3201234567890001',
            'husband_name' => 'Bapak Joseph',
            'phone_number' => '0812999999',
            'address' => 'Alamat Ibu',
            'social_security_number' => '12345678',
            'birth_place' => 'Jakarta',
            'birth_date' => '1990-05-15',
            'blood_type' => 'O',
            'height' => '160',
            'weight' => '55',
            'status' => 'hamil',
        ], $override));
    }

    protected function createTestChild(Mother $mother, array $override = []): Children
    {
        return Children::create(array_merge([
            'name' => 'Anak Tommy',
            'mother_id' => $mother->id,
            'gender' => 'male',
            'birth_place' => 'Jakarta',
            'birth_date' => '2023-01-01',
            'birth_weight' => '3.2',
            'birth_height' => '50',
        ], $override));
    }

    /**
     * Test soft deletes and cascade deletes for Mother.
     */
    public function test_mother_soft_deletes_cascades_to_children_and_records(): void
    {
        $mother = $this->createTestMother();
        $child = $this->createTestChild($mother);

        $pregnancy = PregnancyRecord::create([
            'mother_id' => $mother->id,
            'staff_id' => $this->kaderStaff->id,
            'visit_date' => '2026-06-25',
            'pregnancy_order' => 1,
            'gestational_age' => '12 Minggu',
            'weight' => 60,
            'arm_circumference' => 24.5,
            'blood_pressure' => '120/80',
        ]);

        $childbirth = ChildbirthRecord::create([
            'mother_id' => $mother->id,
            'children_id' => $child->id,
            'staff_id' => $this->kaderStaff->id,
            'child_order' => 1,
            'delivery_method' => 'Normal',
            'delivery_date' => '2026-06-25',
            'delivery_location' => 'Rumah Sakit',
            'baby_condition' => 'Sehat',
        ]);

        // Assert they exist in database
        $this->assertDatabaseHas('mothers', ['id' => $mother->id]);
        $this->assertDatabaseHas('childrens', ['id' => $child->id]);
        $this->assertDatabaseHas('pregnancy_records', ['id' => $pregnancy->id]);
        $this->assertDatabaseHas('childbirth_records', ['id' => $childbirth->id]);

        // Act: Delete Mother (Soft delete)
        $mother->delete();

        // Assert: Mother is soft deleted (not in active table but exists in trashed)
        $this->assertSoftDeleted('mothers', ['id' => $mother->id]);

        // Assert: Cascade soft deletes triggered
        $this->assertSoftDeleted('childrens', ['id' => $child->id]);
        $this->assertSoftDeleted('pregnancy_records', ['id' => $pregnancy->id]);
        $this->assertSoftDeleted('childbirth_records', ['id' => $childbirth->id]);

        // Act: Restore Mother
        $mother->restore();

        // Assert: Everything restored
        $this->assertDatabaseHas('mothers', ['id' => $mother->id, 'deleted_at' => null]);
        $this->assertDatabaseHas('childrens', ['id' => $child->id, 'deleted_at' => null]);
        $this->assertDatabaseHas('pregnancy_records', ['id' => $pregnancy->id, 'deleted_at' => null]);
        $this->assertDatabaseHas('childbirth_records', ['id' => $childbirth->id, 'deleted_at' => null]);
    }

    /**
     * Test staff scoping for normal staff on the dashboard.
     */
    public function test_anggota_kader_sees_scoped_dashboard_stats(): void
    {
        $mother = $this->createTestMother();
        $child1 = $this->createTestChild($mother, ['name' => 'Anak Alice']);
        $child2 = $this->createTestChild($mother, ['name' => 'Anak Bob']);

        // Checked by kaderStaff
        GrowthMonitoring::create([
            'child_id' => $child1->id,
            'staff_id' => $this->kaderStaff->id,
            'checkup_date' => '2026-06-25',
            'weight' => 10,
            'height' => 80,
            'status' => 'Normal',
            'z_score' => 0.0,
        ]);

        // Checked by adminStaff
        GrowthMonitoring::create([
            'child_id' => $child2->id,
            'staff_id' => $this->adminStaff->id,
            'checkup_date' => '2026-06-25',
            'weight' => 11,
            'height' => 82,
            'status' => 'Normal',
            'z_score' => 0.1,
        ]);

        // Login as regular Kader
        $response = $this->actingAs($this->kaderUser)->get(route('dashboard'));

        $response->assertStatus(200);
        
        // Under our controller refactor, kaderStaff has 1 child assigned (Alice)
        // because Alice has GrowthMonitoring by kaderStaff.
        $response->assertViewHas('totalChildren', 1);

        // Login as Ketua Kader (Admin)
        $adminResponse = $this->actingAs($this->adminUser)->get(route('dashboard'));
        $adminResponse->assertStatus(200);
        // Ketua Kader has access to all children
        $adminResponse->assertViewHas('totalChildren', 2);
    }

    /**
     * Test redirect URL and response when editing forms with locked patient details.
     */
    public function test_form_creation_prepopulates_patient_id(): void
    {
        $mother = $this->createTestMother();

        $response = $this->actingAs($this->kaderUser)->get(route('pregnancy-records.create', ['mother_id' => $mother->id]));

        $response->assertStatus(200);
        $response->assertSee('value="' . $mother->id . '"', false);
    }

    /**
     * Test the Archives menu operations (list, restore, forceDelete).
     */
    public function test_archives_menu_functionality(): void
    {
        $mother = $this->createTestMother(['name' => 'Ibu Deleted']);

        // Soft delete the mother
        $mother->delete();

        // Access the Archive listing
        $response = $this->actingAs($this->adminUser)->get(route('archives.index', ['type' => 'Mother']));
        $response->assertStatus(200);
        $response->assertSee('Ibu Deleted');

        // Restore the mother via the Archive route
        $restoreResponse = $this->actingAs($this->adminUser)->post(route('archives.restore', ['type' => 'Mother', 'id' => $mother->id]));
        $restoreResponse->assertRedirect();
        
        // Assert recovered
        $this->assertDatabaseHas('mothers', ['id' => $mother->id, 'deleted_at' => null]);

        // Now soft delete again and permanent delete
        $mother->delete();
        $this->assertSoftDeleted('mothers', ['id' => $mother->id]);

        $forceDeleteResponse = $this->actingAs($this->adminUser)->delete(route('archives.force-delete', ['type' => 'Mother', 'id' => $mother->id]));
        $forceDeleteResponse->assertRedirect();

        // Assert permanently deleted
        $this->assertDatabaseMissing('mothers', ['id' => $mother->id]);
    }
}
