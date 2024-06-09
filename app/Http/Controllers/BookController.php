<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('index','show');
    }

    public function index()
    {
        return Book::all();
    }
    public function store(Request $request)
    {
        try {
            $books = $request->input('books') ? $request->input('books') : [$request->all()];

            $request->validate([
                'books' => 'array',
                'books.*.title' => 'required|string|max:255',
                'books.*.author' => 'required|string|max:255',
                'books.*.publisher' => 'required|string|max:255',
                'books.*.firstPubDate' => 'required|date',
                'books.*.ifTranslator' => 'nullable|string|max:255',
                'books.*.description' => 'required|string',
                'books.*.isbn' => 'required|string|unique:books,isbn|max:20',
                'books.*.pages' => 'required|integer',
                'books.*.ifChapters' => 'nullable|string|max:255',
                'books.*.cover' => 'nullable|string'
            ]);
    
            $createdBooks = [];
            foreach ($books as $bookData) {
                $validator = \Validator::make($bookData, [
                    'title' => 'required|string|max:255',
                    'author' => 'required|string|max:255',
                    'publisher' => 'required|string|max:255',
                    'firstPubDate' => 'required|date',
                    'ifTranslator' => 'nullable|string|max:255',
                    'description' => 'required|string',
                    'isbn' => 'required|string|unique:books,isbn|max:20',
                    'pages' => 'required|integer',
                    'ifChapters' => 'nullable|string|max:255',
                    'cover' => 'nullable|string'
                ]);
    
                if ($validator->fails()) {
                    return response()->json([
                        'message' => 'Validation Error',
                        'errors' => $validator->errors()
                    ], 422);
                }
    
                $createdBooks[] = Book::create($bookData);
            }
    
            return response()->json($createdBooks, 201);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors occurred
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            // Database error occurred
            return response()->json([
                'message' => 'Database Error',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            // Other unexpected errors occurred
            return response()->json([
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

        
    public function show(Request $request)
    {
        $request->validate([
            'search_key' => 'required|string',
            'search_value' => 'required|string',
        ]);
    
        $searchKey = $request->input('search_key');
        $searchValue = $request->input('search_value');
    
        $query = Book::where($searchKey, 'like', "%$searchValue%")->get();
    
        if ($query->isEmpty()) {
            return response()->json(['message' => 'No books found'], 404);
        }
    
        return response()->json($query);
    }

    public function update(Request $request)
    {
        $request->validate([
            'search_key' => 'required|string',
            'search_value' => 'required|string',
            'title' => 'sometimes|string|max:255',
            'author' => 'sometimes|string|max:255',
            'publisher' => 'sometimes|string|max:255',
            'first_publish_date' => 'sometimes|date',
            'ifTranslator' => 'sometimes|boolean',
            'description' => 'sometimes|string',
            'isbn' => 'sometimes|string|unique:books,isbn,' . $request->input('search_value') . '|max:13',
            'pages' => 'sometimes|integer',
            'ifChapters' => 'sometimes|boolean',
            'cover' => 'nullable|string'
        ]);
    
        $searchKey = $request->input('search_key');
        $searchValue = $request->input('search_value');
    
        $book = Book::where($searchKey, $searchValue)->first();
    
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
    
        $book->update($request->all());
    
        return response()->json(['message' => 'Book updated successfully']);
    }
    
    public function destroy(Request $request)
    {
        $request->validate([
            'search_key' => 'required|string',
            'search_value' => 'required|string',
        ]);
    
        $searchKey = $request->input('search_key');
        $searchValue = $request->input('search_value');
    
        $book = Book::where($searchKey, $searchValue)->first();
    
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
    
        $book->delete();
    
        return response()->json(['message' => 'Book deleted successfully']);
    }
}