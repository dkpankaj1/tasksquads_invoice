<x-app-layout>

    <x-breadcrumbs :render="Breadcrumbs::render('settings.edit')" />

    <div class="card">
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Brand Settings -->
                <div class="row">
                    <div class="col-12">
                        <h4 class="mb-3">Brand Settings</h4>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <x-input-label name="brand_name" text="Brand Name" />
                            <x-input-field name="brand_name" type="text"
                                value="{{ old('brand_name', $setting->brand_name ?? '') }}"
                                placeholder="Enter Brand name" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <x-input-label name="cin" text="CIN" />
                            <x-input-field name="cin" type="text" value="{{ old('cin', $setting->cin ?? '') }}"
                                placeholder="Enter CIN" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <x-input-label name="gstin" text="GSTIN" />
                            <x-input-field name="gstin" type="text"
                                value="{{ old('gstin', $setting->gstin ?? '') }}" placeholder="Enter GSTIN" />
                        </div>
                    </div>
                </div>

                <!-- Brand Images -->
                <div class="row mt-4 justify-items-center">
                    <div class="col-12">
                        <h4 class="mb-3">Brand Images</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo (274*43)</label>
                            <div class="form-control d-flex justify-content-center align-items-center my-1 bg-dark"
                                style="height: 100px">
                                <img src="{{ $setting->app_logo }}" alt="Logo" height="60">
                            </div>
                            <input type="file" class="form-control" name="logo" accept="image/*">
                            @error('logo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="stamp" class="form-label">Stamp (Invoice Footer)</label>
                            <div class="form-control d-flex justify-content-center align-items-center my-1"
                                style="height: 100px">
                                <img src="{{ $setting->stamp_image }}" alt="Logo" height="60">
                            </div>
                            <input type="file" class="form-control" name="stamp" accept="image/*">
                            @error('stamp')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>

                <!-- Bank Details -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h4 class="mb-3">Bank Details</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <x-input-label name="beneficiary_name" text="Beneficiary Name" />
                            <x-input-field name="beneficiary_name" type="text"
                                value="{{ old('beneficiary_name', $setting->beneficiary_name ?? '') }}"
                                placeholder="Enter beneficiary name" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <x-input-label name="bank_name" text="Bank Name" />
                            <x-input-field name="bank_name" type="text"
                                value="{{ old('bank_name', $setting->bank_name ?? '') }}"
                                placeholder="Enter bank name" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <x-input-label name="account_type" text="Account Type" />
                            <x-input-field name="account_type" type="text"
                                value="{{ old('account_type', $setting->account_type ?? '') }}"
                                placeholder="Current / Savings" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <x-input-label name="account_number" text="Account Number" />
                            <x-input-field name="account_number" type="text"
                                value="{{ old('account_number', $setting->account_number ?? '') }}"
                                placeholder="Enter account number" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <x-input-label name="ifsc_code" text="IFSC Code" />
                            <x-input-field name="ifsc_code" type="text"
                                value="{{ old('ifsc_code', $setting->ifsc_code ?? '') }}"
                                placeholder="Enter IFSC code" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <x-input-label name="swift_bic_code" text="SWIFT/BIC Code" />
                            <x-input-field name="swift_bic_code" type="text"
                                value="{{ old('swift_bic_code', $setting->swift_bic_code ?? '') }}"
                                placeholder="Enter SWIFT/BIC code" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <x-input-label name="branch" text="Branch" />
                            <x-input-field name="branch" type="text"
                                value="{{ old('branch', $setting->branch ?? '') }}" placeholder="Enter branch" />
                        </div>
                    </div>
                    <div class="col-md-4">

                    </div>
                </div>

                <!-- Contact Information -->
                <div class="row mt-4">

                    <div class="col-12">
                        <h4 class="mb-3">Address Information</h4>
                    </div>

                    <div class="col-md-8">
                        <div class="mb-3">
                            <x-input-label name="address" text="Address" />
                            <x-input-field name="address" type="text"
                                value="{{ old('address', $setting->address ?? '') }}"
                                placeholder="Enter address..." />
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <x-input-label name="city" text="City" />
                            <x-input-field name="city" type="text"
                                value="{{ old('city', $setting->city ?? '') }}" placeholder="Enter city..." />
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <x-input-label name="state" text="State" />
                            <x-input-field name="state" type="text"
                                value="{{ old('state', $setting->state ?? '') }}" placeholder="Enter state..." />
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <x-input-label name="postal_code" text="Postal Code" />
                            <x-input-field name="postal_code" type="text"
                                value="{{ old('postal_code', $setting->postal_code ?? '') }}"
                                placeholder="Enter postal code..." />
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <x-input-label name="country" text="Country" />
                            <x-input-field name="country" type="text"
                                value="{{ old('country', $setting->country ?? '') }}"
                                placeholder="Enter country..." />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <x-input-label name="contact_email" text="Contact Email" />
                            <x-input-field name="contact_email" type="text"
                                value="{{ old('contact_email', $setting->contact_email ?? '') }}"
                                placeholder="example@gmail.com" />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <x-input-label name="contact_phone" text="Contact Phone" />
                            <x-input-field name="contact_phone" type="text"
                                value="{{ old('contact_phone', $setting->contact_phone ?? '') }}"
                                placeholder="+91-9919xxxx55" />
                        </div>
                    </div>

                </div>

                <!-- Social Media Links -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h4 class="mb-3">Social Media Links</h4>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="facebook_link" class="form-label">Facebook</label>
                            <input type="url" class="form-control @error('facebook_link') is-invalid @enderror"
                                id="facebook_link" name="facebook_link"
                                value="{{ old('facebook_link', $setting->facebook_link ?? '') }}">
                            @error('facebook_link')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="twitter_link" class="form-label">Twitter</label>
                            <input type="url" class="form-control @error('twitter_link') is-invalid @enderror"
                                id="twitter_link" name="twitter_link"
                                value="{{ old('twitter_link', $setting->twitter_link ?? '') }}">
                            @error('twitter_link')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="instagram_link" class="form-label">Instagram</label>
                            <input type="url" class="form-control @error('instagram_link') is-invalid @enderror"
                                id="instagram_link" name="instagram_link"
                                value="{{ old('instagram_link', $setting->instagram_link ?? '') }}">
                            @error('instagram_link')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="linkedin_link" class="form-label">LinkedIn</label>
                            <input type="url" class="form-control @error('linkedin_link') is-invalid @enderror"
                                id="linkedin_link" name="linkedin_link"
                                value="{{ old('linkedin_link', $setting->linkedin_link ?? '') }}">
                            @error('linkedin_link')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Meta Information -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h4 class="mb-3">Meta Information</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="meta_title" class="form-label">Meta Title</label>
                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                id="meta_title" name="meta_title"
                                value="{{ old('meta_title', $setting->meta_title ?? '') }}" required>
                            @error('meta_title')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
                                name="meta_description" rows="2" required>{{ old('meta_description', $setting->meta_description ?? '') }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                            <textarea class="form-control @error('meta_keywords') is-invalid @enderror" id="meta_keywords" name="meta_keywords"
                                rows="2" required>{{ old('meta_keywords', $setting->meta_keywords ?? '') }}</textarea>
                            @error('meta_keywords')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="mt-4">
                    <hr>
                    <x-save-btn />
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
