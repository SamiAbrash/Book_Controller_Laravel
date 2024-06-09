<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class WantToReadController extends Controller
{
    public function AddToWantToRead(Request $request, $bookId)
    {
        $user = Auth::user();
        $book = Book::findOrFail($bookId);

        if ($user->wantToRead()->where('book_id', $bookId)->exists()) {
            return response()->json(['message' => 'Book already in Want to Read list.'], 400);
        }

        $user->wantToRead()->attach($book);

        return response()->json(['message' => 'book added to list']);
    }

    public function removeFromWantToRead(Request $request, $bookId)
    {
        $user = Auth::user();
        $book = Book::findOrFail($bookId);
        $user->wantToRead()->detach($book);
        return response()->json(['message' => 'book removed from list']);
    }

    public function getBooksFromWantToRead(Request $request)
    {
        $user = Auth::user();
        $books = $user->wantToRead()->get();
        return response()->json($books);
    }
}
