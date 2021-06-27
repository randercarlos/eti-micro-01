<?php

namespace Tests\Feature\Api;

use App\Http\Resources\CompanyResource;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    private $endpoint = '/companies';

    public function test_it_can_list_companies()
    {
        Company::factory()->count(6)->create();

        $response = $this->getJson($this->endpoint);

        $response
            ->assertJsonCount(6, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name' , 'category' , 'url', 'phone', 'email', 'whatsapp' , 'facebook', 'instagram',
                        'youtube']
                ]
            ])
            ->assertOk();
    }

    public function test_it_can_show_a_company()
    {
        $company = new CompanyResource(Company::factory()->create());

        $response = $this->getJson("{$this->endpoint}/{$company->id}");

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => ['id', 'name' , 'category' , 'url', 'phone', 'email', 'whatsapp' , 'facebook', 'instagram',
                    'youtube']
            ]);
    }

    public function test_it_cant_show_a_nonexistent_company()
    {
        $response = $this->getJson("{$this->endpoint}/any-id");

        $response
            ->assertNotFound();
    }

    public function test_it_cant_create_a_company_without_required_fields()
    {
        $response = $this->postJson($this->endpoint, [
            'name' => null,
            'category_id' => null,
            'email' => null
        ]);

        $response
            ->assertStatus(422);
    }

    public function test_it_can_create_a_company()
    {
        Category::factory()->create();

        $response = $this->postJson($this->endpoint, [
            'name' => 'Fake Company',
            'category_id' => 1,
            'phone' => '(21) 2687-0378',
            'email'=> 'contact@fakecompany.com',
            'whatsapp' => '(21) 99645-0602',
            'facebook' => 'https://facebook.com/fakecompany',
            'instagram' => 'https://instagram.com/fakecompany',
            'youtube' => 'https://youtube.com/fakecompany'
        ]);

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'data' => ['id', 'name' , 'category' , 'url', 'phone', 'email', 'whatsapp' , 'facebook', 'instagram',
                    'youtube']
            ])
            ->assertJsonFragment([
                'id' => 1,
                'name' => 'Fake Company',
                'url' => 'fake-company',
                'phone' => '(21) 2687-0378',
                'email'=> 'contact@fakecompany.com',
                'whatsapp' => '(21) 99645-0602',
                'facebook' => 'https://facebook.com/fakecompany',
                'instagram' => 'https://instagram.com/fakecompany',
                'youtube' => 'https://youtube.com/fakecompany'
            ]);

        $this->assertDatabaseCount('companies', 1);
        $this->assertDatabaseHas('companies', [
            'id' => 1,
            'category_id' => 1,
            'name' => 'Fake Company',
            'url' => 'fake-company',
            'phone' => '(21) 2687-0378',
            'email'=> 'contact@fakecompany.com',
            'whatsapp' => '(21) 99645-0602',
            'facebook' => 'https://facebook.com/fakecompany',
            'instagram' => 'https://instagram.com/fakecompany',
            'youtube' => 'https://youtube.com/fakecompany'
        ]);
    }

    public function test_it_cant_update_a_nonexistent_company()
    {
        Company::factory()->create([
            'name' => 'Fake Company',
            'phone' => '(21) 2687-0378',
            'email'=> 'contact@fakecompany.com',
            'whatsapp' => '(21) 99645-0602',
            'facebook' => 'https://facebook.com/fakecompany',
            'instagram' => 'https://instagram.com/fakecompany',
            'youtube' => 'https://youtube.com/fakecompany'
        ]);

        $response = $this->putJson("{$this->endpoint}/fake-url", [
            'name' => 'Fake Company 2',
            'category_id' => 1,
        ]);

        $response
            ->assertNotFound();

        $this->assertDatabaseCount('companies', 1);
        $this->assertDatabaseHas('companies', [
            'id' => 1,
            'category_id' => 1,
            'name' => 'Fake Company',
            'phone' => '(21) 2687-0378',
            'email'=> 'contact@fakecompany.com',
            'whatsapp' => '(21) 99645-0602',
            'facebook' => 'https://facebook.com/fakecompany',
            'instagram' => 'https://instagram.com/fakecompany',
            'youtube' => 'https://youtube.com/fakecompany'
        ]);
    }

    public function test_it_cant_update_a_company_without_required_fields()
    {
        $company = Company::factory()->create([
            'name' => 'Fake Company',
            'phone' => '(21) 2687-0378',
            'email'=> 'contact@fakecompany.com',
            'whatsapp' => '(21) 99645-0602',
            'facebook' => 'https://facebook.com/fakecompany',
            'instagram' => 'https://instagram.com/fakecompany',
            'youtube' => 'https://youtube.com/fakecompany'
        ]);

        $response = $this->putJson("{$this->endpoint}/{$company->id}", [
            'name' => null,
            'category_id' => null,
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'name', 'category_id'
            ]);

        $this->assertDatabaseCount('companies', 1);
        $this->assertDatabaseHas('companies', [
            'id' => 1,
            'name' => 'Fake Company',
            'url' => 'fake-company',
            'phone' => '(21) 2687-0378',
            'email'=> 'contact@fakecompany.com',
            'whatsapp' => '(21) 99645-0602',
            'facebook' => 'https://facebook.com/fakecompany',
            'instagram' => 'https://instagram.com/fakecompany',
            'youtube' => 'https://youtube.com/fakecompany'
        ]);
    }

    public function test_it_can_update_a_company()
    {
        $company = Company::factory()->create([
            'name' => 'Fake Company',
            'phone' => '(21) 2687-0378',
            'email'=> 'contact@fakecompany.com',
            'whatsapp' => '(21) 99645-0602',
            'facebook' => 'https://facebook.com/fakecompany',
            'instagram' => 'https://instagram.com/fakecompany',
            'youtube' => 'https://youtube.com/fakecompany'
        ]);

        $response = $this->putJson("{$this->endpoint}/{$company->id}", [
            'name' => 'Fake Company 2',
            'category_id' => 1,
            'phone' => '(21) 2687-0378',
            'email'=> 'contact@fakecompany2.com',
            'whatsapp' => '(21) 99645-0602',
            'facebook' => 'https://facebook.com/fakecompany2',
            'instagram' => 'https://instagram.com/fakecompany2',
            'youtube' => 'https://youtube.com/fakecompany2'
        ]);

        $response
            ->assertOk()
            ->assertJsonMissingValidationErrors('data')
            ->assertJsonStructure([
                'data' => ['id', 'name' , 'category' , 'url', 'phone', 'email', 'whatsapp' , 'facebook', 'instagram',
                    'youtube']
            ])
            ->assertJsonFragment([
                'id' => 1,
                'name' => 'Fake Company 2',
                'url' => 'fake-company-2',
                'phone' => '(21) 2687-0378',
                'email'=> 'contact@fakecompany2.com',
                'whatsapp' => '(21) 99645-0602',
                'facebook' => 'https://facebook.com/fakecompany2',
                'instagram' => 'https://instagram.com/fakecompany2',
                'youtube' => 'https://youtube.com/fakecompany2'
            ]);

        $this->assertDatabaseCount('companies', 1);
        $this->assertDatabaseHas('companies', [
            'id' => 1,
            'name' => 'Fake Company 2',
            'url' => 'fake-company-2',
            'phone' => '(21) 2687-0378',
            'email'=> 'contact@fakecompany2.com',
            'whatsapp' => '(21) 99645-0602',
            'facebook' => 'https://facebook.com/fakecompany2',
            'instagram' => 'https://instagram.com/fakecompany2',
            'youtube' => 'https://youtube.com/fakecompany2'
        ]);
    }

    public function test_it_cant_delete_a_nonexistent_company()
    {
        Company::factory()->create([
            'name' => 'Company 01',
        ]);

        $response = $this->deleteJson("{$this->endpoint}/fake-url");

        $response
            ->assertNotFound();

        $this->assertDatabaseCount('companies', 1);
        $this->assertDatabaseHas('companies', [
            'id' => 1,
            'name' => 'Company 01',
        ]);
    }

    public function test_it_can_delete_a_company()
    {
        $company = Company::factory()->create([
            'name' => 'Company 01',
        ]);

        $response = $this->deleteJson("{$this->endpoint}/{$company->id}");

        $response
            ->assertNoContent();

        $this->assertDatabaseCount('companies', 0);
        $this->assertDatabaseMissing('companies', [
            'id' => 1,
            'name' => 'Company 01',
        ]);
    }
}
