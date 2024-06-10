<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookScraper;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * List all Books
     */
    public function index(): View
    {
        return view('book.index', [
            'books' => Book::all()
        ]);
    }

    /**
     * Scrape books from books.to_scrape.com
     */
    public function scrape(Request $request): View
    {
        try {
            $urls = ['category/books/nonfiction_13/index.html'];
            $response = (new BookScraper($urls))->process();

            if ( $response['status'] == 'completed' && ($response['error'] ?? '') == null ) {
                $request->session()->flash('notice', 'Successfully scraped url!');
            } else {
                $request->session()->flash('alert', $response['error']);
            }
        } catch ( \Exception $e ) {
            $request->session()->flash('alert', $e->getMessage());
        }

        return view('book.scrape', []);
    }
}