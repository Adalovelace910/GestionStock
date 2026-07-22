<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\In;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Out;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SauvegardeController extends Controller
{
    public function index()
    {
        return view('admin.sauvegarde.index');
    }

    public function exportProduits(): StreamedResponse
    {
        return $this->exportCsv('produits.csv', ['Nom', 'Description', 'Quantité', 'Prix'], function ($handle) {
            foreach (Product::all() as $product) {
                fputcsv($handle, [$product->nom, $product->description, $product->quantite, $product->prix]);
            }
        });
    }

    public function exportFournisseurs(): StreamedResponse
    {
        return $this->exportCsv('fournisseurs.csv', ['Nom', 'Téléphone', 'Email', 'Adresse'], function ($handle) {
            foreach (Supplier::all() as $supplier) {
                fputcsv($handle, [$supplier->nom, $supplier->telephone, $supplier->email, $supplier->adresse]);
            }
        });
    }

    public function exportMouvements(): StreamedResponse
    {
        return $this->exportCsv('mouvements_stock.csv', ['Type', 'Produit', 'Quantité', 'Date'], function ($handle) {
            foreach (In::with('produit')->get() as $entree) {
                fputcsv($handle, ['Entrée', $entree->produit->nom ?? '—', $entree->quantite, $entree->date_entree->format('Y-m-d')]);
            }

            foreach (Out::with('produit')->get() as $sortie) {
                fputcsv($handle, ['Sortie', $sortie->produit->nom ?? '—', $sortie->quantite, $sortie->date_sortie->format('Y-m-d')]);
            }
        });
    }

    private function exportCsv(string $filename, array $headers, callable $writer): StreamedResponse
    {
        $callback = function () use ($headers, $writer) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);
            $writer($handle);
            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}