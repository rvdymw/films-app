<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class FilmController extends Controller
{
    public function index()
{
    return Film::all();
}

public function store(Request $request)
{
    $data = $request->validate([
        'title' => 'required|string',
        'genre' => 'required|string',
        'description' => 'required|string',
        'country' => 'required|string',
    ]);

    $film = Film::create($data);

    if ($request->hasFile('cover')) {
        $coverPath = $request->file('cover')->store('covers');
        $film->cover = $this->resizeAndSaveCover($coverPath);
        $film->save();
    }

    if ($request->has('genres')) {
        $genres = Genre::whereIn('name', $request->input('genres'))->get();
        $film->genres()->sync($genres);
    }

    return $film;
}

public function show(Film $film)
{
    return $film;
}

public function update(Request $request, Film $film)
{
    $data = $request->validate([
        'title' => 'string',
        'genre' => 'string',
        'description' => 'string',
        'country' => 'string',
    ]);

    $film->update($data);

    if ($request->hasFile('cover')) {
        $coverPath = $request->file('cover')->store('covers');
        $film->cover = $this->resizeAndSaveCover($coverPath);
        $film->save();
    }

    if ($request->has('genres')) {
        $genres = Genre::whereIn('name', $request->input('genres'))->get();
        $film->genres()->sync($genres);
    }

    return $film;
}

public function destroy(Film $film)
{
    $film->delete();
    return response()->noContent();
}

public function search(Request $request)
{
    $query = $request->input('query');
    $films = Film::where('title', 'like', "%$query%")->get();
    return $films;
}

protected function resizeAndSaveCover($coverPath)
{
    $image = Image::make(storage_path('app/' . $coverPath))
        ->fit(300, 400) // Ustaw wymiary przeskalowanej okładki
        ->encode('jpg', 80); // Format i jakość obrazu

    $newCoverPath = 'covers/' . uniqid() . '.jpg';
    Storage::put($newCoverPath, $image);

    return $newCoverPath;
}


public function rate(Request $request, Film $film)
{
    $user_id = auth()->user()->id;
    $rating = $request->input('rating');

    $existingRating = Rating::where('film_id', $film->id)
        ->where('user_id', $user_id)
        ->first();

    if ($existingRating) {
        $existingRating->rating = $rating;
        $existingRating->save();
    } else {
        Rating::create([
            'film_id' => $film->id,
            'user_id' => $user_id,
            'rating' => $rating,
        ]);
    }

    return response()->json(['message' => 'Rating added successfully']);
}
}
