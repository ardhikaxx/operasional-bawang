<?php

namespace App\Http\Controllers\AuditLog;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereDate('created_at', '>=', $request->dari)->whereDate('created_at', '<=', $request->sampai);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('modul')) {
            $query->where('modul', $request->modul);
        }

        if ($request->filled('aksi')) {
            $query->where('aksi', $request->aksi);
        }

        $logs  = $query->orderBy('created_at', 'desc')->paginate(15);
        $users = User::orderBy('name')->get();
        $moduls= AuditLog::select('modul')->distinct()->pluck('modul');

        return view('audit-log.index', compact('logs', 'users', 'moduls'));
    }

    public function show($id)
    {
        $log = AuditLog::with('user')->findOrFail($id);
        return response()->json($log);
    }
}
