<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Products</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="container">
                    <div
                        class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between mb-3 gap-2">
                        <h1 class="mb-0">Products</h1>
                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            <input id="productSearch" type="search" class="form-control flex-fill flex-sm-auto"
                                placeholder="Search name, code, location...">
                            <button id="btnAdd" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#productModal">Add Product</button>
                        </div>
                    </div>
                    @if (session('success'))
                        <div id="flashSuccess" class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover" id="productsTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="d-none d-sm-table-cell">Img</th>
                                    <th class="d-none d-sm-table-cell">Code</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th class="d-none d-sm-table-cell">Stock</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="cursor-pointer">
                                @foreach ($products as $i => $p)
                                    <tr data-id="{{ $p->id }}" data-code="{{ $p->product_code }}"
                                        data-name="{{ $p->product_name }}"
                                        data-category="{{ $p->category?->category_name }}"
                                        data-stock="{{ $p->product_stock }}" data-location="{{ $p->location }}"
                                        data-date="{{ $p->category_date?->format('Y-m-d') }}"
                                        data-image="{{ $p->product_image ? asset('storage/' . $p->product_image) : '' }}">
                                        <td>{{ $products->firstItem() + $i }}</td>
                                        <td class="d-none d-sm-table-cell">
                                            @if ($p->product_image)
                                                <img src="{{ asset('storage/' . $p->product_image) }}" alt=""
                                                    style="width:48px;height:48px;object-fit:cover;border-radius:4px;" />
                                            @else
                                                &mdash;
                                            @endif
                                        </td>
                                        <td class="d-none d-sm-table-cell">{{ $p->product_code }}</td>
                                        <td>{{ $p->product_name }}</td>
                                        <td>{{ $p->category?->category_name }}</td>
                                        <td class="d-none d-sm-table-cell">{{ $p->product_stock }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button title="Edit" class="btn btn-sm btn-outline-primary btn-edit"
                                                    data-id="{{ $p->id }}" data-code="{{ $p->product_code }}"
                                                    data-name="{{ $p->product_name }}"
                                                    data-category_id="{{ $p->category_id }}"
                                                    data-stock="{{ $p->product_stock }}"
                                                    data-location="{{ $p->location }}"
                                                    data-date="{{ $p->category_date?->format('Y-m-d') }}"
                                                    data-image="{{ $p->product_image ? asset('storage/' . $p->product_image) : '' }}"
                                                    data-bs-toggle="modal" data-bs-target="#productModal">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M15.232 5.232l3.536 3.536M3 21l6-1 11-11a2.828 2.828 0 10-4-4L6 16l-1 6z" />
                                                    </svg>
                                                </button>
                                                <form method="POST" action="{{ route('products.destroy', $p) }}"
                                                    class="d-inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" title="Delete"
                                                        class="btn btn-sm btn-outline-danger btn-delete">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="1.5"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div>
                            {{ $products->links() }}
                        </div>
                    </div>

                    <!-- Product detail modal -->
                    <div class="modal fade" id="productDetailModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Product details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-4" id="prodDetailImgWrap" style="display:none;">
                                            <img id="prodDetailImg" src="" alt=""
                                                style="width:100%;height:auto;object-fit:cover;border-radius:6px;" />
                                        </div>
                                        <div class="col-md-8">
                                            <dl class="row">
                                                <dt class="col-sm-4">Code</dt>
                                                <dd class="col-sm-8" id="prodDetailCode"></dd>
                                                <dt class="col-sm-4">Name</dt>
                                                <dd class="col-sm-8" id="prodDetailName"></dd>
                                                <dt class="col-sm-4">Category</dt>
                                                <dd class="col-sm-8" id="prodDetailCategory"></dd>
                                                <dt class="col-sm-4">Stock</dt>
                                                <dd class="col-sm-8" id="prodDetailStock"></dd>
                                                <dt class="col-sm-4">Location</dt>
                                                <dd class="col-sm-8" id="prodDetailLocation"></dd>
                                                <dt class="col-sm-4">Date</dt>
                                                <dd class="col-sm-8" id="prodDetailDate"></dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="#" id="prodDetailEdit" class="btn btn-primary">Edit</a>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="productModalLabel">Product</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="productForm" method="POST" action="{{ route('products.store') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" id="product_id" name="product_id"
                                            value="{{ old('product_id') }}">
                                        <input type="hidden" id="_method" name="_method" value="">
                                        <div id="productImagePreviewWrapper" class="mb-3" style="display:none;">
                                            <label class="form-label">Current Image</label>
                                            <div>
                                                <img id="productImagePreview" src="" alt=""
                                                    style="max-width:120px;max-height:120px;object-fit:cover;border-radius:6px;" />
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Product Code</label>
                                            <input type="text" id="product_code" name="product_code"
                                                class="form-control" value="{{ old('product_code') }}">
                                            @error('product_code')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Product Name</label>
                                            <input type="text" id="product_name" name="product_name"
                                                class="form-control" value="{{ old('product_name') }}">
                                            @error('product_name')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Category</label>
                                            <select id="category_id" name="category_id" class="form-select">
                                                @foreach ($categories as $c)
                                                    <option value="{{ $c->id }}"
                                                        {{ old('category_id') == $c->id ? 'selected' : '' }}>
                                                        {{ $c->category_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Stock</label>
                                            <input type="number" id="product_stock" name="product_stock"
                                                class="form-control" value="{{ old('product_stock') }}">
                                            @error('product_stock')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Location</label>
                                            <input type="text" id="location" name="location"
                                                class="form-control" value="{{ old('location') }}">
                                            @error('location')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Image</label>
                                            <input type="file" id="product_image" name="product_image"
                                                class="form-control">
                                            @error('product_image')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Date</label>
                                            <input type="date" id="category_date" name="category_date"
                                                class="form-control" value="{{ old('category_date') }}">
                                            @error('category_date')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" form="productForm">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const productModalEl = document.getElementById('productModal');
                    const productForm = document.getElementById('productForm');

                    // reset form when modal hidden
                    productModalEl.addEventListener('hidden.bs.modal', function() {
                        productForm.reset();
                        document.getElementById('product_id').value = '';
                        document.getElementById('_method').value = '';
                        productForm.action = '{{ route('products.store') }}';
                        // clear image preview
                        const previewWrap = document.getElementById('productImagePreviewWrapper');
                        const previewImg = document.getElementById('productImagePreview');
                        if (previewImg) previewImg.src = '';
                        if (previewWrap) previewWrap.style.display = 'none';
                    });

                    // edit buttons
                    document.querySelectorAll('.btn-edit').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = this.dataset.id;
                            document.getElementById('product_id').value = id;
                            document.getElementById('product_code').value = this.dataset.code;
                            document.getElementById('product_name').value = this.dataset.name;
                            document.getElementById('product_stock').value = this.dataset.stock;
                            document.getElementById('location').value = this.dataset.location;
                            document.getElementById('category_date').value = this.dataset.date || '';
                            document.getElementById('category_id').value = this.dataset.category_id;
                            productForm.action = '/products/' + id;
                            document.getElementById('_method').value = 'PUT';
                            // set image preview if available
                            const imageUrl = this.dataset.image || '';
                            const previewWrap = document.getElementById('productImagePreviewWrapper');
                            const previewImg = document.getElementById('productImagePreview');
                            if (imageUrl && previewImg && previewWrap) {
                                previewImg.src = imageUrl;
                                previewWrap.style.display = '';
                            } else if (previewWrap) {
                                previewImg.src = '';
                                previewWrap.style.display = 'none';
                            }
                        });
                    });

                    // delete buttons
                    document.querySelectorAll('.btn-delete').forEach(btn => {
                        btn.addEventListener('click', function() {
                            if (!confirm('Delete this product?')) return;
                            this.closest('form').submit();
                        });
                    });

                    // product search filter
                    const productSearch = document.getElementById('productSearch');
                    const productsTable = document.getElementById('productsTable');
                    if (productSearch && productsTable) {
                        productSearch.addEventListener('input', function() {
                            const q = this.value.toLowerCase().trim();
                            productsTable.querySelectorAll('tbody tr').forEach(row => {
                                const code = (row.cells[2] && row.cells[2].textContent || '').toLowerCase();
                                const name = (row.cells[3] && row.cells[3].textContent || '').toLowerCase();
                                const location = (row.cells[5] && row.cells[5].textContent || '')
                                    .toLowerCase();
                                if (!q || code.includes(q) || name.includes(q) || location.includes(q)) {
                                    row.style.display = '';
                                } else {
                                    row.style.display = 'none';
                                }
                            });
                        });
                    }

                    // auto-dismiss flash
                    const flash = document.getElementById('flashSuccess');
                    if (flash) setTimeout(() => {
                        flash.style.display = 'none';
                    }, 3500);

                    @if ($errors->any() && ($errors->hasAny(['product_code', 'product_name', 'category_id']) || old('product_id')))
                        var modal = new bootstrap.Modal(productModalEl);
                        modal.show();
                    @endif

                    // Open modal if ?edit={id} parameter is present
                    (function() {
                        const params = new URLSearchParams(window.location.search);
                        const editId = params.get('edit');
                        if (!editId) return;
                        const btn = document.querySelector('.btn-edit[data-id="' + editId + '"]');
                        if (btn) {
                            btn.click();
                            // remove query param to avoid reopening on refresh
                            params.delete('edit');
                            const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() :
                                '');
                            window.history.replaceState({}, document.title, newUrl);
                        }
                    })();

                    // row click -> show product detail modal (ignore clicks on action buttons)
                    const detailModalEl = document.getElementById('productDetailModal');
                    if (productsTable) {
                        productsTable.querySelectorAll('tbody tr').forEach(row => {
                            row.addEventListener('click', function(e) {
                                // ignore if clicked on a button inside the row
                                if (e.target.closest('button') || e.target.closest('a') || e.target.closest(
                                        'form')) return;

                                const id = this.dataset.id || '';
                                const code = this.dataset.code || '';
                                const name = this.dataset.name || '';
                                const category = this.dataset.category || '';
                                const stock = this.dataset.stock || '';
                                const location = this.dataset.location || '';
                                const date = this.dataset.date || '';
                                const image = this.dataset.image || '';

                                document.getElementById('prodDetailCode').textContent = code;
                                document.getElementById('prodDetailName').textContent = name;
                                document.getElementById('prodDetailCategory').textContent = category;
                                document.getElementById('prodDetailStock').textContent = stock;
                                document.getElementById('prodDetailLocation').textContent = location;
                                document.getElementById('prodDetailDate').textContent = date;

                                const imgWrap = document.getElementById('prodDetailImgWrap');
                                const imgEl = document.getElementById('prodDetailImg');
                                if (image) {
                                    imgEl.src = image;
                                    imgWrap.style.display = '';
                                } else {
                                    imgEl.src = '';
                                    imgWrap.style.display = 'none';
                                }

                                const editLink = document.getElementById('prodDetailEdit');
                                if (editLink) editLink.href = '{{ route('products.index') }}?edit=' + id;

                                var modal = new bootstrap.Modal(detailModalEl);
                                modal.show();
                            });
                        });
                    }
                });
            </script>
</x-app-layout>
