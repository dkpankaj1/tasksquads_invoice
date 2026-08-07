/**
 * Reusable AJAX Form Handler Class
 * Handles form submission with validation error display and callbacks
 */
class AjaxFormHandler {
    constructor(options) {
        this.form = options.form;
        this.button = options.button;
        this.url = options.url;
        this.method = options.method || "POST";
        this.onSuccess = options.onSuccess || (() => {});
        this.onError = options.onError || null;
        this.resetFormOnSuccess = options.resetFormOnSuccess !== false; // Default true
        this.processData =
            options.processData !== undefined ? options.processData : false;
        this.contentType =
            options.contentType !== undefined ? options.contentType : false;
        this.showLoadingState = options.showLoadingState !== false; // Default true
        this.modalToHide = options.modalToHide || null;

        this.init();
    }

    /**
     * Initialize the form handler
     */
    init() {
        if (!this.form || !this.button || !this.url) {
            console.error(
                "AjaxFormHandler: form, button, and url are required"
            );
            return;
        }

        this.bindEvents();
    }

    /**
     * Bind click event to submit button
     */
    bindEvents() {
        $(this.button).on("click", (e) => {
            e.preventDefault();
            this.submitForm();
        });
    }

    /**
     * Submit the form via AJAX
     */
    submitForm() {
        const formData = new FormData(this.form);

        // Clear previous validation errors
        this.clearValidationErrors();

        // Show loading state
        if (this.showLoadingState) {
            this.setLoadingState(true);
        }

        $.ajax({
            url: this.url,
            method: this.method,
            data: formData,
            processData: this.processData,
            contentType: this.contentType,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: (response) => {
                this.handleSuccess(response);
            },
            error: (xhr) => {
                this.handleError(xhr);
            },
            complete: () => {
                // Hide loading state
                if (this.showLoadingState) {
                    this.setLoadingState(false);
                }
            },
        });
    }

    /**
     * Handle successful response
     */
    handleSuccess(response) {
        // Hide modal if specified
        if (this.modalToHide) {
            $(this.modalToHide).modal("hide");
        }

        // Reset form if enabled
        if (this.resetFormOnSuccess) {
            this.form.reset();
        }

        // Call success callback
        this.onSuccess(response);
    }

    /**
     * Handle error response
     */
    handleError(xhr) {
        if (xhr.status === 422) {
            // Validation errors
            const errors = xhr.responseJSON?.errors;
            if (errors) {
                this.displayValidationErrors(errors);
            }
        } else {
            // Other errors
            const errorMessage =
                xhr.responseJSON?.message ||
                "An error occurred. Please try again.";

            if (this.onError) {
                this.onError(xhr, errorMessage);
            } else {
                console.error("Form submission failed:", xhr.responseText);
                alert(errorMessage);
            }
        }
    }

    /**
     * Display validation errors next to form fields
     */
    displayValidationErrors(errors) {
        Object.keys(errors).forEach((field) => {
            const errorMessages = errors[field];
            const fieldElement = this.form.querySelector(`[name="${field}"]`);

            if (fieldElement) {
                // Add error class to field
                fieldElement.classList.add("is-invalid");

                // Create error message element
                const errorDiv = document.createElement("div");
                errorDiv.className = "invalid-feedback text-danger";
                errorDiv.textContent = errorMessages[0];

                // Insert error message after the field
                fieldElement.parentNode.appendChild(errorDiv);
            }
        });
    }

    /**
     * Clear all validation errors from form
     */
    clearValidationErrors() {
        this.form.querySelectorAll(".is-invalid").forEach((element) => {
            element.classList.remove("is-invalid");
        });

        this.form.querySelectorAll(".invalid-feedback").forEach((element) => {
            element.remove();
        });
    }

    /**
     * Set loading state on submit button
     */
    setLoadingState(isLoading) {
        const $button = $(this.button);

        if (isLoading) {
            $button.prop("disabled", true);
            const originalText = $button.text();
            $button.data("original-text", originalText);
            $button.html('<i class="fas fa-spinner fa-spin"></i> Loading...');
        } else {
            $button.prop("disabled", false);
            const originalText = $button.data("original-text") || "Submit";
            $button.text(originalText);
        }
    }
}
