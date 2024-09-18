@extends('layouts.master')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-8 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between end">
                                <h4 class="card-title">Edit Product</h4>
                                <a href="{{ route('product.index') }}" class="btn btn-primary btn-sm">Back List</a>
                            </div>

                            <form method="POST" action="{{ route('product.update', $product->id) }}" class="forms-sample">
                                @csrf
                                @method('PUT')

                                <!-- Product Name -->
                                <div class="form-group">
                                    <label for="productName">Product Name:<span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name', $product->name) }}" placeholder="Name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div class="form-group">
                                    <label for="category">Category:<span class="text-danger">*</span></label>
                                    <select class="form-control @error('category_id') is-invalid @enderror"
                                        name="category_id">
                                        <option selected disabled value="">Select</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Price -->
                                <div class="form-group">
                                    <label for="price">Price:<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('price') is-invalid @enderror"
                                        name="price" value="{{ old('price', $product->price) }}" placeholder="Price">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Attributes -->
                                <div class="form-group">
                                    <label for="attributes">Attributes:<span class="text-danger">*</span></label>
                                    <div id="attributes-container">
                                        @if (old('attributes'))
                                            @foreach (old('attributes') as $index => $attribute)
                                                <div class="input-group mb-2" id="attribute-row-{{ $index }}">
                                                    <select class="form-control" id="attribute-select-{{ $index }}" onchange="showValueField({{ $index }})">
                                                        <option selected disabled value="">Select Attribute</option>
                                                        @foreach ($attributes as $attr)
                                                            <option value="{{ $attr->id }}" {{ $attribute['id'] == $attr->id ? 'selected' : '' }}>
                                                                {{ $attr->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="value-field" id="value-field-{{ $index }}" style="margin-left: 10px;">
                                                        <input type="hidden" name="attributes[{{ $index }}][id]" value="{{ $attribute['id'] }}">
                                                        <input type="text" class="form-control" name="attributes[{{ $index }}][value]" value="{{ $attribute['value'] }}" placeholder="Enter value">
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeAttribute({{ $index }})">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        @else
                                            @foreach ($product->attributes as $index => $attribute)
                                                <div class="input-group mb-2" id="attribute-row-{{ $index }}">
                                                    <select class="form-control" id="attribute-select-{{ $index }}" onchange="showValueField({{ $index }})">
                                                        <option selected disabled value="">Select Attribute</option>
                                                        @foreach ($attributes as $attr)
                                                            <option value="{{ $attr->id }}" {{ $attribute->id == $attr->id ? 'selected' : '' }}>
                                                                {{ $attr->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="value-field" id="value-field-{{ $index }}" style="margin-left: 10px;">
                                                        <input type="hidden" name="attributes[{{ $index }}][id]" value="{{ $attribute->id }}">
                                                        <input type="text" class="form-control" name="attributes[{{ $index }}][value]" value="{{ $attribute->pivot->value }}" placeholder="Enter value">
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeAttribute({{ $index }})">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="addAttribute()">Add Attribute</button>
                                </div>

                                <!-- Display Errors -->
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary me-2">Update</button>
                                <a href="{{ route('product.index') }}" class="btn btn-light">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Script for handling attributes --}}
<script>
    let attributeIndex = {{ count(old('attributes', $product->attributes)) }};
    let attributes = @json($attributes);

    function addAttribute() {
        const container = document.getElementById('attributes-container');
        const currentIndex = attributeIndex;

        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.id = `attribute-row-${currentIndex}`;

        const selectedValues = Array.from(document.querySelectorAll('[name^="attributes["][name$="[id]"]')).map(input => input.value);
        const availableAttributes = attributes.filter(attr => !selectedValues.includes(attr.id.toString()));

        div.innerHTML = `
            <select class="form-control" id="attribute-select-${currentIndex}" onchange="showValueField(${currentIndex})">
                <option selected disabled value="">Select Attribute</option>
                ${availableAttributes.map(attribute => `<option value="${attribute.id}">${attribute.name}</option>`).join('')}
            </select>
            <div class="value-field" id="value-field-${currentIndex}" style="display:none; margin-left: 10px;">
                <input type="hidden" name="attributes[${currentIndex}][id]" id="attribute-id-${currentIndex}">
                <input type="text" class="form-control" name="attributes[${currentIndex}][value]" placeholder="Enter value">
            </div>
        `;

        container.appendChild(div);
        attributeIndex++;
    }

    function showValueField(index) {
        const select = document.getElementById(`attribute-select-${index}`);
        const selectedAttributeId = select.value;

        const hiddenInput = document.getElementById(`attribute-id-${index}`);
        hiddenInput.value = selectedAttributeId;

        const valueField = document.getElementById(`value-field-${index}`);
        valueField.style.display = 'block';
    }

</script>
