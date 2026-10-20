<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Portfolio;
use App\Models\Product;
use Illuminate\Http\Response;

class StaticPageController extends Controller
{
    public function privacy()
    {
        return view('privacy');
    }

    public function terms()
    {
        return view('terms');
    }

    public function sitemap(): Response
    {
        $urls = collect([
            ['loc' => route('home'), 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => route('products.index'), 'changefreq' => 'daily', 'priority' => '0.9'],
            ['loc' => route('portfolio.index'), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => route('blog.index'), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => route('about'), 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => route('contact'), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => route('privacy'), 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['loc' => route('terms'), 'changefreq' => 'yearly', 'priority' => '0.3'],
        ]);

        foreach (Product::where('status', true)->get() as $product) {
            $urls->push(['loc' => route('products.show', $product), 'changefreq' => 'weekly', 'priority' => '0.7']);
        }

        foreach (Portfolio::all() as $portfolio) {
            $urls->push(['loc' => route('portfolio.show', $portfolio), 'changefreq' => 'monthly', 'priority' => '0.6']);
        }

        foreach (BlogPost::published()->get() as $post) {
            $urls->push(['loc' => route('blog.show', $post), 'changefreq' => 'monthly', 'priority' => '0.6']);
        }

        return response()->view('sitemap', compact('urls'))->header('Content-Type', 'application/xml');
    }
}
