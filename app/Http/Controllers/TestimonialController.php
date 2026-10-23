<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('sort_order')->latest()->paginate(12);

        return view('dashboard.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        $testimonial = new Testimonial(['is_active' => true, 'rating' => 5]);

        return view('dashboard.testimonials.form', compact('testimonial'));
    }

    public function store(Request $request)
    {
        Testimonial::create($this->validated($request));

        return redirect()->route('testimonials.index')->with('success', 'Testimoni berhasil ditambahkan.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('dashboard.testimonials.form', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $testimonial->update($this->validated($request));

        return redirect()->route('testimonials.index')->with('success', 'Testimoni berhasil diperbarui.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return redirect()->route('testimonials.index')->with('success', 'Testimoni berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'location' => 'nullable|string|max:100',
            'content' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
            'sort_order' => 'nullable|integer',
        ]);

        return [
            'name' => $request->name,
            'location' => $request->location,
            'content' => $request->content,
            'rating' => $request->integer('rating'),
            'sort_order' => $request->integer('sort_order'),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
