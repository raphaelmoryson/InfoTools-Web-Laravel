<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderByDesc('created_at')->paginate(10);
        return view('customer.index', compact('customers'));
    }

    public function create()
    {
        $statuses = ['prospect', 'actif', 'inactif', 'perdu'];
        return view('customer.create', compact('statuses'));
    }
    public function purchases(Request $request)
    {
        $customer = Customer::with(['invoices.lines.product'])
            ->findOrFail($request->query('customer_id'));

        $invoices = $customer->invoices()
            ->with('lines.product')
            ->orderByDesc('invoiced_at')
            ->paginate(10);

        return view('customer.purchases', compact('customer', 'invoices'));
    }
    public function store(Request $request)
    {
        $statuses = ['prospect', 'actif', 'inactif', 'perdu'];

        $validated = $request->validate([
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'name' => ['nullable', 'string', 'max:200'],
            'company_name' => ['nullable', 'string', 'max:200'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('customers', 'email')],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'country' => ['nullable', 'string', 'max:120'],
            'status' => ['required', Rule::in($statuses)],
            'last_contact_at' => ['nullable', 'date'],
            'next_meeting_at' => ['nullable', 'date'],
            'total_spent' => ['nullable', 'numeric', 'min:0'],
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        // Par défaut, assigne le commercial connecté si non fourni
        if (empty($validated['user_id']) && auth()->check()) {
            $validated['user_id'] = auth()->id();
        }

        // Valeur par défaut cohérente
        $validated['country'] = $validated['country'] ?? 'France';
        $validated['status'] = $validated['status'] ?? 'prospect';
        // Concatène first_name + last_name dans name
        $validated['name'] = trim(($validated['first_name'] ?? '') . ' ' . ($validated['last_name'] ?? ''));
        Customer::create($validated);

        return redirect()
            ->route('customer.index')
            ->with('success', 'Client créé avec succès.');
    }

    public function edit(Customer $customer)
    {
        $statuses = ['prospect', 'actif', 'inactif', 'perdu'];
        return view('customer.edit', compact('customer', 'statuses'));
    }

    public function update(Request $request, Customer $customer)
    {
        $statuses = ['prospect', 'actif', 'inactif', 'perdu'];

        $validated = $request->validate([
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'name' => ['nullable', 'string', 'max:200'],
            'company_name' => ['nullable', 'string', 'max:200'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('customers', 'email')->ignore($customer->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'country' => ['nullable', 'string', 'max:120'],
            'status' => ['required', Rule::in($statuses)],
            'last_contact_at' => ['nullable', 'date'],
            'next_meeting_at' => ['nullable', 'date'],
            'total_spent' => ['nullable', 'numeric', 'min:0'],
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        $customer->update($validated);

        return redirect()
            ->route('customer.index')
            ->with('success', 'Client mis à jour avec succès.');
    }

    public function show(Customer $customer)
    {
        // On charge les factures et leurs lignes pour la vue
        $customer->load(['invoices.lines.product']);

        // On récupère les factures paginées (pour que le lien ->links() fonctionne)
        $invoices = $customer->invoices()
            ->orderByDesc('invoiced_at')
            ->paginate(10);

        return view('customer.show', compact('customer', 'invoices'));
    }
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()
            ->route('customer.index')
            ->with('success', 'Client supprimé avec succès.');
    }
}
