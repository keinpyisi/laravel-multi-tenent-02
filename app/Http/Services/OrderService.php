<?php

namespace App\Http\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\OrderInterface;

class OrderService {
    protected $orderRepository;

    public function __construct(OrderInterface $orderRepository) {
        $this->orderRepository = $orderRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
        return $this->orderRepository->index();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //
        return $this->orderRepository->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
        return $this->orderRepository->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
        return $this->orderRepository->show($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        //
        return $this->orderRepository->edit($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
        return $this->orderRepository->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        return $this->orderRepository->destroy($id);
        //
    }
}
