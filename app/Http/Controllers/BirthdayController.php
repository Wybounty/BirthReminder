<?php

namespace App\Http\Controllers;

use App\Models\Birthday;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;

class BirthdayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $user = Auth::user();

        $birthdays = Birthday::where('user_id', $user->id)
            ->get()
            ->map(function ($birthday) {
                $today = Carbon::today();
                $birthDate = Carbon::parse($birthday->birth_date);

                // Âge actuel
                $birthday->age = $birthDate->age;

                // Prochain anniversaire
                $nextBirthday = Carbon::create(
                    $today->year,
                    $birthDate->month,
                    $birthDate->day
                );

                if ($nextBirthday->lt($today)) {
                    $nextBirthday->addYear();
                }

                $birthday->next_birthday = $nextBirthday;

                // Nombre de jours avant le prochain anniversaire
                $birthday->days_until_birthday = $today->diffInDays($nextBirthday);

                return $birthday;
            })
            ->sortBy('days_until_birthday')
            ->values();

        return Inertia::render('dashboard', [
            'birthdays' => $birthdays,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('birthdays/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date', 'before_or_equal:today'],
            'email' => ['required', 'email', 'max:255'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('birthdays', 'public');
        }

        $request->user()->birthdays()->create($data);

        if($data['birth_date'] > date('Y-m-d')) {
            Inertia::flash('toast', [
                'type' => 'warning',
                'message' => __('Date invalide.'),
            ]);
            return back();
        }

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Anniversaire ajouté avec succès.'),
        ]);

        return to_route('home');
    }

    /**
     * Display the specified resource.
     */
    public function show(Birthday $birthday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Birthday $birthday): Response
    {
        abort_unless($birthday->user_id === $request->user()->id, 404);

        return Inertia::render('birthdays/edit', ['birthday' => $birthday]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Birthday $birthday): RedirectResponse
    {
        abort_unless($birthday->user_id === $request->user()->id, 404);

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date', 'before_or_equal:today'],
            'email' => ['required', 'email', 'max:255'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        if ($request->hasFile('photo')) {
            if ($birthday->photo) {
                Storage::disk('public')->delete($birthday->photo);
            }

            $data['photo'] = $request->file('photo')->store('birthdays', 'public');
        } else {
            unset($data['photo']);
        }

        $birthday->update($data);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Anniversaire modifié avec succès.'),
        ]);

        return to_route('home');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Birthday $birthday): RedirectResponse
    {
        abort_unless($birthday->user_id === $request->user()->id, 404);

        if ($birthday->photo) {
            Storage::disk('public')->delete($birthday->photo);
        }

        $birthday->delete();

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Anniversaire supprimé avec succès.'),
        ]);

        return to_route('home');
    }
}
