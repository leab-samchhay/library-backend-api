<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\BookReturn;
use App\Models\Category;
use App\Models\Fines;
use App\Models\Loan;
use App\Models\LoanDetail;
use App\Models\Member;
use App\Models\Publishers;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
    }

    public function test_login_authenticates_user(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@gmail.com',
            'password' => '123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function test_loan_details_late_returns_and_overdue_routes_work(): void
    {
        $admin = User::where('email', 'admin@gmail.com')->first();

        $member = Member::create([
            'member_code' => 'M001',
            'first_name' => 'Sok',
            'last_name' => 'Dara',
        ]);

        $category = Category::create([
            'category_name' => 'Programming',
            'description' => 'Coding books',
        ]);

        $publisher = Publishers::create([
            'publishers_name' => 'O\'Reilly',
        ]);

        $book = Book::create([
            'category_id' => $category->id,
            'publishers_id' => $publisher->id,
            'title' => 'Laravel Up & Running',
            'isbn' => '978-1492041214',
            'qty' => 5,
        ]);

        $bookCopy = BookCopy::create([
            'book_id' => $book->id,
            'barcode' => 'BC0001',
            'status' => 'Available',
            'condition' => 'Good',
            'price' => 25,
        ]);

        $loan = Loan::create([
            'member_id' => $member->id,
            'staff_id' => 'ST001',
            'loan_date' => now()->subDays(10)->toDateString(),
            'due_date' => now()->subDays(3)->toDateString(),
            'status' => 'Active',
        ]);

        $loanDetail = LoanDetail::create([
            'loan_id' => $loan->id,
            'book_copy_id' => $bookCopy->id,
            'status' => 'Borrowed',
        ]);

        // Test overdue loans
        $overdueResponse = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/loan-details/overdue');

        $overdueResponse->assertStatus(200)
            ->assertJsonPath('message', 'បញ្ជីអ្នកដែលមិនទាន់សង និងហួសថ្ងៃកំណត់');

        // Return the book late
        $bookReturn = BookReturn::create([
            'loan_detail_id' => $loanDetail->id,
            'returned_date' => now()->toDateString(),
            'received_by' => 'Librarian',
        ]);

        // Test late returns
        $lateResponse = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/loan-details/late-returns');

        $lateResponse->assertStatus(200)
            ->assertJsonPath('message', 'បញ្ជីអ្នកដែលបានសងយឺតថ្ងៃ');
    }

    public function test_book_copies_category_name_filter_works(): void
    {
        $admin = User::where('email', 'admin@gmail.com')->first();

        $category = Category::create([
            'category_name' => 'Science',
            'description' => 'Science books',
        ]);

        $publisher = Publishers::create([
            'publishers_name' => 'Penguin',
        ]);

        $book = Book::create([
            'category_id' => $category->id,
            'publishers_id' => $publisher->id,
            'title' => 'Cosmos',
            'isbn' => '978-0345331359',
            'qty' => 3,
        ]);

        BookCopy::create([
            'book_id' => $book->id,
            'barcode' => 'BC0002',
            'status' => 'Available',
            'condition' => 'Good',
            'price' => 30,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/book-copies?category_name=Science');

        $response->assertStatus(200)
            ->assertJsonPath('message', 'get data success');
    }

    public function test_fines_show_loads_with_relationship(): void
    {
        $admin = User::where('email', 'admin@gmail.com')->first();

        $member = Member::create([
            'member_code' => 'M002',
            'first_name' => 'Chhai',
            'last_name' => 'Sok',
        ]);

        $category = Category::create([
            'category_name' => 'History',
            'description' => 'History books',
        ]);

        $publisher = Publishers::create([
            'publishers_name' => 'History Press',
        ]);

        $book = Book::create([
            'category_id' => $category->id,
            'publishers_id' => $publisher->id,
            'title' => 'Angkor History',
            'isbn' => '978-1234567890',
            'qty' => 1,
        ]);

        $bookCopy = BookCopy::create([
            'book_id' => $book->id,
            'barcode' => 'BC0003',
            'status' => 'Available',
            'condition' => 'Good',
            'price' => 15,
        ]);

        $loan = Loan::create([
            'member_id' => $member->id,
            'staff_id' => 'ST002',
            'loan_date' => now()->subDays(5)->toDateString(),
            'due_date' => now()->toDateString(),
            'status' => 'Active',
        ]);

        $loanDetail = LoanDetail::create([
            'loan_id' => $loan->id,
            'book_copy_id' => $bookCopy->id,
            'status' => 'Returned',
        ]);

        $bookReturn = BookReturn::create([
            'loan_detail_id' => $loanDetail->id,
            'returned_date' => now()->toDateString(),
            'received_by' => 'Staff',
        ]);

        $fine = Fines::create([
            'return_id' => $bookReturn->id,
            'fine_type' => 'Late Return',
            'amount' => 5.00,
            'status' => 'Unpaid',
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/fines/'.$fine->id);

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $fine->id);
    }

    public function test_roles_show_returns_role(): void
    {
        $admin = User::where('email', 'admin@gmail.com')->first();

        $role = Role::create([
            'name' => 'Manager',
            'description' => 'Library Manager',
            'is_active' => 1,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/role/'.$role->id);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Manager');
    }

    public function test_dashboard_summary_works(): void
    {
        $admin = User::where('email', 'admin@gmail.com')->first();

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/report/getSummary');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'total_users',
                    'total_members',
                    'total_books',
                    'total_spent',
                    'books_info',
                    'loan_info',
                    'return_info',
                ],
                'message',
            ]);
    }
}
