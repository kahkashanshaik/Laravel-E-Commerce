<?php
/** @var \Illuminate\Database\Eloquent\Collection $orders */
?>
<x-app-layout>
    <main class="p-5">
        <div class="container lg:w-2/3 xl:w-2/3 mx-auto">
          <h1 class="text-3xl font-bold mb-6">My Orders</h1>
  
          <div class="bg-white p-3 rounded-md shadow-md">
            <table class="table table-auto w-full">
              <thead class="border-b-2">
                <tr class="text-left">
                  <th>Order</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Total</th>
                  <th>Items</th>
                  <th >Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($orders as $order)
                <tr class="border-b">
                  <td class="py-2 px-2">
                    <a
                      href="{{route('order.view', $order)}}"
                      class="text-purple-600 hover:text-purple-500"
                    >
                      #{{$order->id}}
                    </a>
                  </td>
                  <td class="py-2 px-2">{{$order->created_at}}</td>
                  <td>
                    <small class="text-white p-1 rounded
                    {{ $order->isPaid() ? 'bg-emerald-500' :  'bg-gray-500 ' }}">
                    {{$order->status}}
                </small>
                  </td>
                  <td class="py-2 px-2">${{$order->total_price}}</td>
                  <td class="py-2 px-2 whitespace-nowrap">{{ $order->items->count() }} Item(s)</td>
                  <td class="py-2 px-2 flex gap-3 justify-center w-[50px]">
                    {{-- <div x-data="{open: false}">
                      <button
                        @click="open = true"
                        class="btn-primary bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 py-1 px-2 flex items-center"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          class="h-5 w-5 mr-1"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke="currentColor"
                          stroke-width="2"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                          />
                        </svg>
                        Invoice
                      </button>
                      <template x-teleport="body">
                        <!-- Backdrop -->
                        <div
                          x-show="open"
                          class="fixed flex justify-center items-center left-0 top-0 bottom-0 right-0 z-10 bg-black/80"
                        >
                          <!-- Dialog -->
                          <div
                            x-show="open"
                            x-transition
                            @click.outside="open = false"
                            class="w-[90%] md:w-1/2 bg-white rounded-lg"
                          >
                            <!-- Modal Title -->
                            <div
                              class="py-2 px-4 text-lg font-semibold bg-gray-100 rounded-t-lg flex items-center justify-between"
                            >
                              <h2>Modal Title</h2>
                              <button @click="open = false">
                                <svg
                                  xmlns="http://www.w3.org/2000/svg"
                                  class="h-6 w-6"
                                  fill="none"
                                  viewBox="0 0 24 24"
                                  stroke="currentColor"
                                  stroke-width="2"
                                >
                                  <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 18L18 6M6 6l12 12"
                                  />
                                </svg>
                              </button>
                            </div>
                            <!-- Modal Body -->
                            <div class="p-4">
                                Invoice Content
                            </div>
                            <!-- Modal Footer -->
                            <div
                              class="py-2 px-4 text-lg flex justify-end font-semibold bg-gray-100 rounded-b-lg"
                            >
                           
                              <button
                                @click="open = false"
                                class="inline-flex items-center py-1 px-3 bg-gray-300 hover:bg-opacity-95 text-gray-800 rounded-md shadow"
                              >
                                Close
                              </button>
                           
                            </div>
                          </div>
                        </div>
                      </template>
                    </div> --}}
                    @if(!$order->isPaid())
                    <form action="{{route('cart.checkout-order', $order)}}" method="POST">
                        @csrf
                    <button class="btn-primary bg-slate-900 text-white py-2 px-2 flex items-center">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 mr-1"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                        />
                      </svg>
                      Pay
                    </button>
                    </form>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            {{ $orders->links() }}
          </div>
        </div>
      </main>
</x-app-layout>
