<?php

namespace App\Http\Controllers;

use App\Models\Penawaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PenawaranDocumentController extends Controller
{
    /**
     * View invoice GSB
     */
    public function invoiceGsb(Penawaran $penawaran)
    {
        return $this->showDocument($penawaran, 'documents.invoices.gsb', 'Invoice_GSB_' . $penawaran->no_penawaran);
    }

    /**
     * View invoice Ritel
     */
    public function invoiceRitel(Penawaran $penawaran)
    {
        return $this->showDocument($penawaran, 'documents.invoices.ritel', 'Invoice_Ritel_' . $penawaran->no_penawaran);
    }

    /**
     * View invoice Corporate
     */
    public function invoiceCorporate(Penawaran $penawaran)
    {
        return $this->showDocument($penawaran, 'documents.invoices.corporate', 'Invoice_Corporate_' . $penawaran->no_penawaran);
    }

    /**
     * View Surat Jalan
     */
    public function suratJalan(Penawaran $penawaran)
    {
        return $this->showDocument($penawaran, 'documents.surat_jalan', 'Surat_Jalan_' . $penawaran->no_penawaran);
    }

    /**
     * View BAS (Berita Acara Survey)
     */
    public function bas(Penawaran $penawaran)
    {
        return $this->showDocument($penawaran, 'documents.bas', 'BAS_' . $penawaran->no_penawaran);
    }

    /**
     * View BAST (Berita Acara Serah Terima)
     */
    public function bast(Penawaran $penawaran)
    {
        return $this->showDocument($penawaran, 'documents.bast', 'BAST_' . $penawaran->no_penawaran);
    }

    /**
     * Show document for printing/downloading
     */
    private function showDocument(Penawaran $penawaran, $view, $filename)
    {
        // Check if penawaran is approved
        if ($penawaran->status !== 'disetujui') {
            return redirect()->back()->with('error', 'Dokumen hanya dapat diakses untuk penawaran yang sudah disetujui');
        }

        // Load related data
        $penawaran->load('client', 'items.material');

        // Return view for printing
        return view($view, [
            'penawaran' => $penawaran,
            'filename' => $filename,
        ]);
    }
}
