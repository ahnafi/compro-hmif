<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $form->title }} - HMIF Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md p-6">
            <!-- Form Header -->
            <div class="mb-6">
                @if($form->thumbnail)
                    <img src="{{ Storage::url($form->thumbnail) }}" alt="{{ $form->title }}" class="w-full h-48 object-cover rounded-lg mb-4">
                @endif
                
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $form->title }}</h1>
                
                @if($form->description)
                    <p class="text-gray-600">{{ $form->description }}</p>
                @endif
                
                @if($form->end_date)
                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                        <p class="text-sm text-yellow-800">
                            <strong>Deadline:</strong> {{ $form->end_date->format('d M Y, H:i') }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Form -->
            <form id="dynamic-form" class="space-y-6">
                @csrf
                
                <!-- Submitter Information -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pengguna</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="submitted_by_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama *
                            </label>
                            <input type="text" id="submitted_by_name" name="submitted_by_name" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="submitted_by_email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email *
                            </label>
                            <input type="email" id="submitted_by_email" name="submitted_by_email" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="submitted_by_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                Nomor Telepon
                            </label>
                            <input type="tel" id="submitted_by_phone" name="submitted_by_phone"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Dynamic Form Fields -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Form</h3>
                    <div class="space-y-4">
                        @foreach($form->fields as $field)
                            @php
                                $fieldName = 'field_' . md5($field['label'] ?? '');
                            @endphp
                            
                            @if($field['type'] === 'heading')
                                <div class="pt-4">
                                    <h4 class="text-xl font-semibold text-gray-900">{{ $field['content'] }}</h4>
                                </div>
                            @elseif($field['type'] === 'paragraph')
                                <div class="text-gray-600">
                                    <p>{{ $field['content'] }}</p>
                                </div>
                            @else
                                <div>
                                    <label for="{{ $fieldName }}" class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $field['label'] }}
                                        @if($field['required'] ?? false)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>
                                    
                                    @if($field['help_text'] ?? false)
                                        <p class="text-sm text-gray-500 mb-2">{{ $field['help_text'] }}</p>
                                    @endif
                                    
                                    @switch($field['type'])
                                        @case('text')
                                        @case('email')
                                        @case('number')
                                            <input type="{{ $field['type'] }}" 
                                                   id="{{ $fieldName }}" 
                                                   name="{{ $fieldName }}"
                                                   placeholder="{{ $field['placeholder'] ?? '' }}"
                                                   @if($field['required'] ?? false) required @endif
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            @break
                                        
                                        @case('textarea')
                                            <textarea id="{{ $fieldName }}" 
                                                      name="{{ $fieldName }}"
                                                      placeholder="{{ $field['placeholder'] ?? '' }}"
                                                      rows="4"
                                                      @if($field['required'] ?? false) required @endif
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                            @break
                                        
                                        @case('date')
                                            <input type="date" 
                                                   id="{{ $fieldName }}" 
                                                   name="{{ $fieldName }}"
                                                   @if($field['required'] ?? false) required @endif
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            @break
                                        
                                        @case('select')
                                            <select id="{{ $fieldName }}" 
                                                    name="{{ $fieldName }}"
                                                    @if($field['required'] ?? false) required @endif
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Pilih opsi...</option>
                                                @foreach($field['options'] ?? [] as $option)
                                                    <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                                @endforeach
                                            </select>
                                            @break
                                        
                                        @case('radio')
                                            <div class="space-y-2">
                                                @foreach($field['options'] ?? [] as $option)
                                                    <label class="flex items-center">
                                                        <input type="radio" 
                                                               name="{{ $fieldName }}" 
                                                               value="{{ $option['value'] }}"
                                                               @if($field['required'] ?? false) required @endif
                                                               class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                                        <span class="text-sm text-gray-900">{{ $option['label'] }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            @break
                                        
                                        @case('checkbox')
                                            <div class="space-y-2">
                                                @foreach($field['options'] ?? [] as $option)
                                                    <label class="flex items-center">
                                                        <input type="checkbox" 
                                                               name="{{ $fieldName }}[]" 
                                                               value="{{ $option['value'] }}"
                                                               @if($field['required'] ?? false) class="required-checkbox" @endif
                                                               class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                        <span class="text-sm text-gray-900">{{ $option['label'] }}</span>
                                                    </label>
                                                @endforeach
                                                @if($field['required'] ?? false)
                                                    <input type="hidden" name="{{ $fieldName }}_required" value="1">
                                                @endif
                                            </div>
                                            @break
                                        
                                        @case('file')
                                            <input type="file" 
                                                   id="{{ $fieldName }}" 
                                                   name="{{ $fieldName }}"
                                                   @if($field['required'] ?? false) required @endif
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            @break
                                    @endswitch
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="border-t pt-6">
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="submit-text">Kirim Form</span>
                        <span id="loading-text" class="hidden">Mengirim...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <div id="message-container" class="fixed top-4 right-4 z-50"></div>

    <script>
        document.getElementById('dynamic-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Validate required checkboxes
            const requiredCheckboxGroups = document.querySelectorAll('input[name$="_required"]');
            let validationError = false;
            
            requiredCheckboxGroups.forEach(hiddenInput => {
                const fieldName = hiddenInput.name.replace('_required', '');
                const checkboxes = document.querySelectorAll(`input[name="${fieldName}[]"]:checked`);
                
                if (checkboxes.length === 0) {
                    showMessage(`Please select at least one option for the required field.`, 'error');
                    validationError = true;
                    return;
                }
            });
            
            if (validationError) return;
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const submitText = document.getElementById('submit-text');
            const loadingText = document.getElementById('loading-text');
            
            // Disable submit button
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            loadingText.classList.remove('hidden');
            
            try {
                const formData = new FormData(this);
                
                const response = await fetch('{{ route("forms.submit", $form->slug) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    showMessage('Form berhasil dikirim!', 'success');
                    this.reset();
                } else {
                    if (result.errors) {
                        let errorMessage = 'Terjadi kesalahan:\n';
                        for (const field in result.errors) {
                            errorMessage += `- ${result.errors[field].join(', ')}\n`;
                        }
                        showMessage(errorMessage, 'error');
                    } else {
                        showMessage(result.error || 'Terjadi kesalahan', 'error');
                    }
                }
            } catch (error) {
                showMessage('Terjadi kesalahan jaringan', 'error');
            } finally {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                loadingText.classList.add('hidden');
            }
        });
        
        function showMessage(message, type) {
            const container = document.getElementById('message-container');
            const div = document.createElement('div');
            
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            
            div.className = `${bgColor} text-white px-6 py-3 rounded-md shadow-lg mb-4 max-w-sm`;
            div.textContent = message;
            
            container.appendChild(div);
            
            setTimeout(() => {
                div.remove();
            }, 5000);
        }
    </script>
</body>
</html>
