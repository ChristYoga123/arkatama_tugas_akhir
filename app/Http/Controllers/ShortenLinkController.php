<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\Visit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShortenLinkController extends Controller
{
    public function index()
    {
        // ambil semua data link yang dimiliki oleh user yang sedang login dan total visitnya seluruh tanggal
        $links = DB::table("links")
            ->select("links.*", DB::raw("SUM(visits.total_visits) as total_visits"))
            ->leftJoin("visits", "links.id", "=", "visits.link_id")
            ->where("links.user_id", auth()->user()->id)
            ->groupBy("links.id")
            ->get();
        return view("pages.shorten-link.index")->with([
            "title" => "ShortenLink",
            "links" => $links,
            "hostname" => request()->getSchemeAndHttpHost(),
        ]);
    }

    public function single(Link $link)
    {
        return response()->json([
            "status" => "success",
            "message" => "Shorten link retrieved successfully",
            "data" => $link,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "original_url" => "required|url",
            "custom_url" => "nullable"
        ]);

        $shortenLink = "";
        if($request->custom_url){
            // cek apakah ada karakter spasinya, jika ada maka hapus spasinya
            if(Str::contains($request->custom_url, " ")){
                $shortenLink = str_replace(" ", "", $request->custom_url);
            } else {
                $shortenLink = $request->custom_url;
            }
        }else{
            $shortenLink = $this->generateRandomString();
        }

        // cek apakah link sudah ada di database
        $shortenedLink = Link::where("shortened_url", $shortenLink)->first();
        $originalLink = Link::where("original_url", $request->original_url)->first();
        if($shortenedLink || $originalLink){
            return response()->json([
                "status" => "error",
                "message" => "The link already exists in the database",
            ], 403);
        }

        // simpan data ke database
        DB::beginTransaction();
        try {
            $link = Link::create([
                "user_id" => auth()->user()->id,
                "original_url" => $request->original_url,
                "shortened_url" => $shortenLink,
            ]);

            // cek apakah user memiliki hak akses untuk membuat link dengan jumlah sesuai role
            $limit = Package::where("name", auth()->user()->roles->first()->name)->first()->max_per_day;
            $totalLink = Link::where("user_id", auth()->user()->id)->whereDate("created_at", now())->count();

            if($totalLink > $limit){
                DB::rollBack();
                return response()->json([
                    "status" => "error",
                    "message" => "You have reached the limit of creating links today",
                ], 403);
            }
            DB::commit();
            return response()->json([
                "status" => "success",
                "message" => "Shorten link created successfully",
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function show($shortenedUrl)
    {
        // jalankan locking untuk mencegah race condition
        DB::beginTransaction();
        try {
            $link = Link::where("shortened_url", $shortenedUrl)->first();
            if(!$link){
                abort(404);
            }
            // get current date
            $date = date("Y-m-d");
            $visit = Visit::where("link_id", $link->id)->where("date", $date)->first();
            if(!$visit){
                $visit = Visit::create([
                    "link_id" => $link->id,
                    "total_visits" => 1,
                    "date" => now(),
                ]);
            } else {
                $visit->total_visits += 1;
                $visit->save();
            }
            DB::commit();
            return redirect($link->original_url);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Link $link)
    {
        $request->validate([
            "original_url" => "required|url",
            "custom_url" => "nullable"
        ]);

        $shortenLink = "";
        if($request->custom_url){
            // cek apakah ada karakter spasinya, jika ada maka hapus spasinya
            if(Str::contains($request->custom_url, " ")){
                $shortenLink = str_replace(" ", "", $request->custom_url);
            } else {
                $shortenLink = $request->custom_url;
            }
        }else{
            $shortenLink = $this->generateRandomString();
        }

        // cek apakah link sudah ada di database
        $shortenedLink = Link::where("shortened_url", $shortenLink)->first();
        $originalLink = Link::where("original_url", $request->original_url)->first();
        if($shortenedLink || $originalLink){
            return response()->json([
                "status" => "error",
                "message" => "The link already exists in the database",
            ], 403);
        }

        // simpan data ke database
        DB::beginTransaction();
        try {
            $link->update([
                "original_url" => $request->original_url,
                "shortened_url" => $shortenLink,
            ]);

            DB::commit();
            return response()->json([
                "status" => "success",
                "message" => "Shorten link updated successfully",
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Link $link)
    {
        Visit::where("link_id", $link->id)->delete();
        $link->delete();
        return response()->json([
            "status" => "success",
            "message" => "Shorten link deleted successfully",
        ]);
    }

    private function generateRandomString($length = 6)
    {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $charactersLength = strlen($characters);
        $randomString = "";
        for($i = 0; $i < $length; $i++){
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
