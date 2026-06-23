<?php
namespace App\Controllers;

class ExploreController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $brands = $db->query("
            SELECT b.*, COUNT(v.id) as ev_count
            FROM brands b
            LEFT JOIN vehicles v ON v.brand_id = b.id AND v.status = 'published'
            WHERE b.status IN ('active', 'published')
            GROUP BY b.id
            ORDER BY b.featured DESC, ev_count DESC, b.name ASC
        ")->getResultArray();

        return view('explore/index', [
            'brands'     => $brands,
            'meta_title' => 'Explore EVs by Brand — Charj.in',
        ]);
    }
}
