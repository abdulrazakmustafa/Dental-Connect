<?php

namespace App\Modules\Identity\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * The public marketing site (PRD §8/§9/§121). No marketplace navigation or
 * content is ever exposed here — this controller renders informational
 * pages only.
 */
class PublicPageController extends Controller
{
    public function home(): View
    {
        return view('public.home');
    }

    public function forPatients(): View
    {
        return view('public.simple', [
            'title' => 'For Patients — Dental Connect',
            'heading' => 'Find a trusted dental clinic, on your terms.',
            'body' => '<p>Search verified clinics by location, service and specialty, enroll securely with the clinic of your choice, and request appointments from a simple, mobile-friendly account.</p>',
            'cta' => ['label' => 'Register as a Patient', 'href' => route('register', ['role' => 'patient'])],
        ]);
    }

    public function forClinics(): View
    {
        return view('public.simple', [
            'title' => 'For Clinics — Dental Connect',
            'heading' => 'Grow your clinic with a verified digital presence.',
            'body' => '<p>Get verified, manage your own patients and appointment requests, showcase your dentists and services, and connect with dental suppliers through a private, permission-based marketplace built for clinics.</p>',
            'cta' => ['label' => 'Register your Clinic', 'href' => route('register', ['role' => 'clinic'])],
        ]);
    }

    public function forSuppliers(): View
    {
        return view('public.simple', [
            'title' => 'For Suppliers — Dental Connect',
            'heading' => 'Reach verified dental clinics across Tanzania.',
            'body' => '<p>Dental Connect connects dental-material suppliers with verified clinics through a private B2B workspace — list your catalogue, respond to quotation requests, and grow your clinic relationships.</p>',
            'cta' => ['label' => 'Register your Company', 'href' => route('register', ['role' => 'supplier'])],
        ]);
    }

    public function about(): View
    {
        return view('public.simple', [
            'title' => 'About — Dental Connect',
            'heading' => 'About Dental Connect',
            'body' => '<p>Dental Connect is a secure ecosystem connecting patients, dental clinics, dentists and dental-material suppliers across Tanzania, starting in Dar es Salaam.</p>',
        ]);
    }

    public function contact(): View
    {
        return view('public.simple', [
            'title' => 'Contact — Dental Connect',
            'heading' => 'Get in touch',
            'body' => '<p>Reach the Dental Connect team at <a class="text-[--color-dc-teal-dark] underline" href="mailto:hello@dentalconnect.co.tz">hello@dentalconnect.co.tz</a>.</p>',
        ]);
    }
}
