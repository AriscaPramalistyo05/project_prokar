<?php

namespace Tests\Feature;

use App\Livewire\Admin\CategoryIndex;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'super_admin']);
        $this->admin = User::factory()->create([
            'email' => 'admin@prokar.test',
        ]);
        $this->admin->assignRole('super_admin');
    }

    public function test_super_admin_can_view_category_index_page(): void
    {
        Category::create(['name' => 'Kulkas', 'slug' => 'kulkas', 'icon' => 'fa-solid fa-snowflake']);

        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Kategori');
        $response->assertSee('Kulkas');
    }

    public function test_super_admin_can_create_new_category(): void
    {
        Livewire::actingAs($this->admin)
            ->test(CategoryIndex::class)
            ->call('openCreateModal')
            ->assertSet('showModal', true)
            ->set('name', 'Air Conditioner')
            ->set('icon', 'fa-solid fa-wind')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showModal', false);

        $this->assertDatabaseHas('categories', [
            'name' => 'Air Conditioner',
            'slug' => 'air-conditioner',
            'icon' => 'fa-solid fa-wind',
        ]);
    }

    public function test_super_admin_can_edit_existing_category(): void
    {
        $category = Category::create([
            'name' => 'TV LED',
            'slug' => 'tv-led',
            'icon' => 'fa-solid fa-tv',
        ]);

        Livewire::actingAs($this->admin)
            ->test(CategoryIndex::class)
            ->call('openEditModal', $category->id)
            ->assertSet('showModal', true)
            ->assertSet('name', 'TV LED')
            ->set('name', 'Smart Televisi')
            ->set('icon', 'fa-solid fa-display')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showModal', false);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Smart Televisi',
            'slug' => 'smart-televisi',
            'icon' => 'fa-solid fa-display',
        ]);
    }

    public function test_validation_prevents_duplicate_category_name(): void
    {
        Category::create(['name' => 'Kulkas', 'slug' => 'kulkas']);

        Livewire::actingAs($this->admin)
            ->test(CategoryIndex::class)
            ->call('openCreateModal')
            ->set('name', 'Kulkas')
            ->call('save')
            ->assertHasErrors(['name']);
    }

    public function test_cannot_delete_category_in_use_by_products(): void
    {
        $category = Category::create(['name' => 'Kulkas', 'slug' => 'kulkas']);
        Product::factory()->create(['category_id' => $category->id]);

        Livewire::actingAs($this->admin)
            ->test(CategoryIndex::class)
            ->call('confirmDelete', $category->id)
            ->call('deleteCategory');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_can_delete_unused_category(): void
    {
        $category = Category::create(['name' => 'Blender', 'slug' => 'blender']);

        Livewire::actingAs($this->admin)
            ->test(CategoryIndex::class)
            ->call('confirmDelete', $category->id)
            ->call('deleteCategory');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
