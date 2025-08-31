<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Categories</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="container">
                    <h1 class="mb-4">Categories</h1>
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <button id="btnAdd" class="btn btn-primary mb-3" data-bs-toggle="modal"
                        data-bs-target="#categoryModal">Add Category</button>

                    <table class="table" id="categoriesTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Products</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $i => $c)
                                <tr>
                                    <td>{{ $categories->firstItem() + $i }}</td>
                                    <td>{{ $c->category_name }}</td>
                                    <td>{{ $c->products_count }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary btn-edit" data-id="{{ $c->id }}"
                                            data-name="{{ $c->category_name }}" data-bs-toggle="modal"
                                            data-bs-target="#categoryModal">Edit</button>

                                        <form method="POST" action="{{ route('categories.destroy', $c) }}"
                                            class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-sm btn-danger btn-delete">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div>
                        {{ $categories->links() }}
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="categoryModalLabel">Category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="categoryForm" method="POST" action="{{ route('categories.store') }}">
                                    @csrf
                                    <input type="hidden" id="category_id" name="category_id"
                                        value="{{ old('category_id') }}">
                                    <input type="hidden" id="_method_cat" name="_method" value="">
                                    <div class="mb-3">
                                        <label class="form-label">Category Name</label>
                                        <input type="text" id="category_name" name="category_name"
                                            class="form-control" value="{{ old('category_name') }}">
                                        @error('category_name')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" form="categoryForm">Save</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const categoryModalEl = document.getElementById('categoryModal');
                const categoryForm = document.getElementById('categoryForm');

                categoryModalEl.addEventListener('hidden.bs.modal', function() {
                    categoryForm.reset();
                    document.getElementById('category_id').value = '';
                    document.getElementById('_method_cat').value = '';
                    categoryForm.action = '{{ route('categories.store') }}';
                });

                document.querySelectorAll('.btn-edit').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        document.getElementById('category_id').value = id;
                        document.getElementById('category_name').value = this.dataset.name;
                        categoryForm.action = '/categories/' + id;
                        document.getElementById('_method_cat').value = 'PUT';
                    });
                });

                document.querySelectorAll('.btn-delete').forEach(btn => {
                    btn.addEventListener('click', function() {
                        if (!confirm('Delete this category?')) return;
                        this.closest('form').submit();
                    });
                });

                @if ($errors->any() && ($errors->has('category_name') || old('category_id')))
                    var modal = new bootstrap.Modal(categoryModalEl);
                    modal.show();
                @endif
            });
        </script>
</x-app-layout>
