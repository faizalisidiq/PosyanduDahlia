<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mother;
use App\Models\Children;
use App\Models\Elderly;
use App\Models\PregnancyRecord;
use App\Models\ChildbirthRecord;
use App\Models\GrowthMonitoring;
use App\Models\IlpScreening;

class ArchiveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $type = strtolower($request->get('type', 'mother'));
        $search = $request->get('search');

        $query = null;
        switch ($type) {
            case 'mother':
                $query = Mother::onlyTrashed()->withCount('children');
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('identity_number', 'like', "%{$search}%");
                    });
                }
                break;
            case 'children':
                $query = Children::onlyTrashed()->with('mother');
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('identity_number', 'like', "%{$search}%");
                    });
                }
                break;
            case 'elderly':
                $query = Elderly::onlyTrashed();
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('identity_number', 'like', "%{$search}%");
                    });
                }
                break;
            case 'pregnancy_record':
                $query = PregnancyRecord::onlyTrashed()->with(['mother', 'staff.user']);
                if ($search) {
                    $query->whereHas('mother', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                }
                break;
            case 'childbirth_record':
                $query = ChildbirthRecord::onlyTrashed()->with(['mother', 'staff.user']);
                if ($search) {
                    $query->whereHas('mother', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                }
                break;
            case 'growth_monitoring':
                $query = GrowthMonitoring::onlyTrashed()->with(['child', 'staff.user']);
                if ($search) {
                    $query->whereHas('child', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                }
                break;
            case 'ilp_screening':
                $query = IlpScreening::onlyTrashed()->with(['subjectable', 'staff.user']);
                if ($search) {
                    $query->whereHasMorph('subjectable', [Mother::class, Children::class, Elderly::class], function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                }
                break;
        }

        if (!$query) {
            abort(400, 'Tipe data tidak valid.');
        }

        $items = $query->latest()->paginate(10)->withQueryString();

        return view('apps.archives.index', compact('items', 'type'));
    }

    public function restore($type, $id)
    {
        try {
            $modelClass = $this->getModelClass($type);
            $record = $modelClass::onlyTrashed()->findOrFail($id);
            $record->restore();

            return redirect()->back()->with('success', 'Data berhasil dikembalikan beserta relasinya.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengembalikan data: ' . $e->getMessage());
        }
    }

    public function forceDelete($type, $id)
    {
        try {
            $modelClass = $this->getModelClass($type);
            $record = $modelClass::onlyTrashed()->findOrFail($id);
            $record->forceDelete();

            return redirect()->back()->with('success', 'Data berhasil dihapus secara permanen.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data secara permanen: ' . $e->getMessage());
        }
    }

    private function getModelClass($type)
    {
        return match (strtolower($type)) {
            'mother' => Mother::class,
            'children' => Children::class,
            'elderly' => Elderly::class,
            'pregnancy_record' => PregnancyRecord::class,
            'childbirth_record' => ChildbirthRecord::class,
            'growth_monitoring' => GrowthMonitoring::class,
            'ilp_screening' => IlpScreening::class,
            default => throw new \Exception('Tipe data tidak valid.'),
        };
    }
}
