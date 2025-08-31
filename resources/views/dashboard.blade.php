<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <style>
                .dash-card {
                    display: flex;
                    align-items: center;
                    gap: 12px
                }

                .dash-icon {
                    width: 40px;
                    height: 40px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    background: #f3f4f6;
                    border-radius: 8px
                }

                .count-to {
                    font-variant-numeric: tabular-nums
                }

                .badge-low {
                    background: #f87171;
                    color: #fff;
                    padding: 2px 8px;
                    border-radius: 999px;
                    font-size: 12px
                }
            </style>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-4 shadow-sm sm:rounded-lg hover:shadow-md transition cursor-pointer"
                    id="card-categories" role="button">
                    <div class="dash-card">
                        <div class="dash-icon">
                            <!-- category icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M3 7h18M3 12h18M3 17h18" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm text-gray-500">Total Categories</h4>
                            <div class="text-2xl font-bold count-to" data-to="{{ $totalCategories ?? 0 }}">0</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 shadow-sm sm:rounded-lg hover:shadow-md transition cursor-pointer"
                    id="card-products" role="button">
                    <div class="dash-card">
                        <div class="dash-icon">
                            <!-- product icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M3 7l9-4 9 4v8l-9 4L3 15V7z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm text-gray-500">Total Products</h4>
                            <div class="text-2xl font-bold count-to" data-to="{{ $totalProducts ?? 0 }}">0</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 shadow-sm sm:rounded-lg hover:shadow-md transition cursor-pointer"
                    id="card-lowstock" role="button">
                    <div class="dash-card">
                        <div class="dash-icon">
                            <!-- low stock icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 8v4l3 3" />
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="text-sm text-gray-500">Low Stock (&lt;5)</h4>
                                <span class="badge-low">{{ $lowStockProducts->count() }}</span>
                            </div>
                            <div class="text-2xl font-bold count-to" data-to="{{ $lowStockProducts->count() ?? 0 }}">0
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <h3 class="font-semibold text-lg text-gray-800 mb-3">Products with low stock</h3>
                <div class="mb-3 flex items-center justify-between">
                    <div>
                        <input id="dashboardSearch" type="search" placeholder="Search name or code..."
                            class="form-control" />
                    </div>
                    <div class="text-sm text-gray-500">Showing {{ $lowStockProducts->count() }} low-stock items</div>
                </div>

                @if (isset($lowStockProducts) && $lowStockProducts->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-striped" id="lowStockTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lowStockProducts as $i => $p)
                                    <tr data-id="{{ $p->id }}" data-code="{{ $p->product_code }}"
                                        data-name="{{ $p->product_name }}"
                                        data-category="{{ $p->category?->category_name }}"
                                        data-stock="{{ $p->product_stock }}" data-location="{{ $p->location }}"
                                        data-date="{{ $p->category_date?->format('Y-m-d') }}"
                                        data-image="{{ $p->product_image ? asset('storage/' . $p->product_image) : '' }}"
                                        class="{{ $p->product_stock !== null && $p->product_stock <= 1 ? 'table-danger' : ($p->product_stock !== null && $p->product_stock <= 3 ? 'table-warning' : '') }}"
                                        style="cursor:pointer;">
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $p->product_code }}</td>
                                        <td>{{ $p->product_name }}</td>
                                        <td>{{ $p->category?->category_name }}</td>
                                        <td>{{ $p->product_stock }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-gray-600">No products with low stock.</div>
                @endif

                <!-- Product detail modal -->
                <div class="modal fade" id="productDetailModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="productDetailModalLabel">Product details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-4" id="detailImageWrap" style="display:none;">
                                        <img id="detailImage" src="" alt=""
                                            style="width:100%;height:auto;object-fit:cover;border-radius:6px;" />
                                    </div>
                                    <div class="col-md-8">
                                        <dl class="row">
                                            <dt class="col-sm-4">Code</dt>
                                            <dd class="col-sm-8" id="detailCode"></dd>

                                            <dt class="col-sm-4">Name</dt>
                                            <dd class="col-sm-8" id="detailName"></dd>

                                            <dt class="col-sm-4">Category</dt>
                                            <dd class="col-sm-8" id="detailCategory"></dd>

                                            <dt class="col-sm-4">Stock</dt>
                                            <dd class="col-sm-8" id="detailStock"></dd>

                                            <dt class="col-sm-4">Location</dt>
                                            <dd class="col-sm-8" id="detailLocation"></dd>

                                            <dt class="col-sm-4">Date</dt>
                                            <dd class="col-sm-8" id="detailDate"></dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="#" id="detailEditLink" class="btn btn-primary">Edit</a>
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cardLow = document.getElementById('card-lowstock');
            const searchInput = document.getElementById('dashboardSearch');
            const lowTable = document.getElementById('lowStockTable');

            if (cardLow) {
                cardLow.addEventListener('click', function() {
                    const el = document.getElementById('lowStockTable');
                    if (el) {
                        el.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                        if (searchInput) searchInput.focus();
                    }
                });
            }

            // client-side filter
            if (searchInput && lowTable) {
                searchInput.addEventListener('input', function() {
                    const q = this.value.toLowerCase().trim();
                    const rows = lowTable.querySelectorAll('tbody tr');
                    rows.forEach(r => {
                        const name = (r.dataset.name || '').toLowerCase();
                        const code = (r.dataset.code || '').toLowerCase();
                        if (!q || name.includes(q) || code.includes(q)) {
                            r.style.display = '';
                        } else {
                            r.style.display = 'none';
                        }
                    });
                });
            }

            // row click => show detail modal
            if (lowTable) {
                lowTable.querySelectorAll('tbody tr').forEach(row => {
                    row.addEventListener('click', function() {
                        const modalEl = document.getElementById('productDetailModal');
                        const code = this.dataset.code || '';
                        const name = this.dataset.name || '';
                        const category = this.dataset.category || '';
                        const stock = this.dataset.stock || '';
                        const location = this.dataset.location || '';
                        const date = this.dataset.date || '';
                        const image = this.dataset.image || '';

                        document.getElementById('detailCode').textContent = code;
                        document.getElementById('detailName').textContent = name;
                        document.getElementById('detailCategory').textContent = category;
                        document.getElementById('detailStock').textContent = stock;
                        document.getElementById('detailLocation').textContent = location;
                        document.getElementById('detailDate').textContent = date;

                        const imgWrap = document.getElementById('detailImageWrap');
                        const imgEl = document.getElementById('detailImage');
                        if (image) {
                            imgEl.src = image;
                            imgWrap.style.display = '';
                        } else {
                            imgEl.src = '';
                            imgWrap.style.display = 'none';
                        }

                        // edit link
                        const editLink = document.getElementById('detailEditLink');
                        if (editLink) {
                            editLink.href = '{{ route('products.index') }}?edit=' + this.dataset
                            .id;
                        }

                        var modal = new bootstrap.Modal(modalEl);
                        modal.show();
                    });
                });
            }

            // simple count-up animation for summary cards
            document.querySelectorAll('.count-to').forEach(el => {
                const to = parseInt(el.dataset.to || '0', 10);
                if (isNaN(to) || to <= 0) {
                    el.textContent = to;
                    return;
                }
                const duration = 800; // ms
                const stepTime = Math.max(20, Math.floor(duration / to));
                let current = 0;
                const step = () => {
                    current += 1;
                    el.textContent = current;
                    if (current < to) {
                        setTimeout(step, stepTime);
                    }
                };
                step();
            });
        });
    </script>
</x-app-layout>
