<?php

namespace App\Http\Controllers\Tenents\Back;

use Illuminate\Http\Request;
use App\Http\Services\OrderService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;



class OrderController extends Controller {
    protected $orderService;

    public function __construct(OrderService $orderService) {
        $this->orderService = $orderService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
        $header_js_defines = [
            'resources/js/tenants/orders/index.js',
        ];
        $header_css_defines = [
            //'resources/css/clients/index.css',
        ];

        // Share the variable globally
        view()->share('header_js_defines', $header_js_defines);
        view()->share('header_css_defines', $header_css_defines);

        $orders = $this->orderService->index();
        log_message('Orders Data ' . $orders);
        return view('tenant.pages.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //
        $orders = $this->orderService->create();
        return $orders;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
        $orders = $this->orderService->store($request);
        return $orders;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
        $orders = $this->orderService->show($id);
        return $orders;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        //
        $orders = $this->orderService->edit($id);
        return $orders;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
        $orders = $this->orderService->update($request, $id);
        return $orders;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
        $orders = $this->orderService->destroy($id);
        return $orders;
    }
}
