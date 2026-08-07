<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Dashboard
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('dashboard'));
});

// Items
Breadcrumbs::for('item.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Items', route('item.index'));
});

Breadcrumbs::for('item.create', function (BreadcrumbTrail $trail) {
    $trail->parent('item.index');
    $trail->push('Create Item', route('item.create'));
});

Breadcrumbs::for('item.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('item.index');
    $trail->push('Edit Item', route('item.edit', $item));
});

// Categories
Breadcrumbs::for('category.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Categories', route('category.index'));
});

Breadcrumbs::for('category.create', function (BreadcrumbTrail $trail) {
    $trail->parent('category.index');
    $trail->push('Create Category', route('category.create'));
});

Breadcrumbs::for('category.edit', function (BreadcrumbTrail $trail, $category) {
    $trail->parent('category.index');
    $trail->push('Edit Category', route('category.edit', $category));
});

// Customers
Breadcrumbs::for('customer.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Customers', route('customer.index'));
});

Breadcrumbs::for('customer.create', function (BreadcrumbTrail $trail) {
    $trail->parent('customer.index');
    $trail->push('Create Customer', route('customer.create'));
});

Breadcrumbs::for('customer.show', function (BreadcrumbTrail $trail, $customer) {
    $trail->parent('customer.index');
    $trail->push('Customer', route('customer.show', $customer));
});

Breadcrumbs::for('customer.edit', function (BreadcrumbTrail $trail, $customer) {
    $trail->parent('customer.index');
    $trail->push('Edit Customer', route('customer.edit', $customer));
});

// Finance Years
Breadcrumbs::for('finance-year.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Finance Years', route('finance-year.index'));
});

Breadcrumbs::for('finance-year.create', function (BreadcrumbTrail $trail) {
    $trail->parent('finance-year.index');
    $trail->push('Create Finance Year', route('finance-year.create'));
});

Breadcrumbs::for('finance-year.show', function (BreadcrumbTrail $trail, $financeYear) {
    $trail->parent('finance-year.index');
    $trail->push('Finance Year', route('finance-year.show', $financeYear));
});

Breadcrumbs::for('finance-year.edit', function (BreadcrumbTrail $trail, $financeYear) {
    $trail->parent('finance-year.index');
    $trail->push('Edit Finance Year', route('finance-year.edit', $financeYear));
});

// Invoices
Breadcrumbs::for('invoice.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Invoices', route('invoice.index'));
});

Breadcrumbs::for('invoice.create', function (BreadcrumbTrail $trail) {
    $trail->parent('invoice.index');
    $trail->push('Create Invoice', route('invoice.create'));
});

Breadcrumbs::for('invoice.show', function (BreadcrumbTrail $trail, $invoice) {
    $trail->parent('invoice.index');
    $trail->push('Invoice', route('invoice.show', $invoice));
});

Breadcrumbs::for('invoice.edit', function (BreadcrumbTrail $trail, $invoice) {
    $trail->parent('invoice.index');
    $trail->push('Edit Invoice', route('invoice.edit', $invoice));
});

Breadcrumbs::for('invoice.pdf', function (BreadcrumbTrail $trail, $invoice) {
    $trail->parent('invoice.show', $invoice);
    $trail->push('PDF', route('invoice.pdf', $invoice));
});

// Payments
Breadcrumbs::for('payment.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Payments', route('payment.index'));
});

Breadcrumbs::for('payment.create', function (BreadcrumbTrail $trail) {
    $trail->parent('payment.index');
    $trail->push('Create Payment', route('payment.create'));
});

Breadcrumbs::for('payment.show', function (BreadcrumbTrail $trail, $payment) {
    $trail->parent('payment.index');
    $trail->push('Payment', route('payment.show', $payment));
});

Breadcrumbs::for('payment.edit', function (BreadcrumbTrail $trail, $payment) {
    $trail->parent('payment.index');
    $trail->push('Edit Payment', route('payment.edit', $payment));
});

// Units
Breadcrumbs::for('unit.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Units', route('unit.index'));
});

Breadcrumbs::for('unit.create', function (BreadcrumbTrail $trail) {
    $trail->parent('unit.index');
    $trail->push('Create Unit', route('unit.create'));
});

Breadcrumbs::for('unit.edit', function (BreadcrumbTrail $trail, $unit) {
    $trail->parent('unit.index');
    $trail->push('Edit Unit ', route('unit.edit', $unit));
});

// Taxes
Breadcrumbs::for('tax.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Taxes', route('tax.index'));
});

Breadcrumbs::for('tax.create', function (BreadcrumbTrail $trail) {
    $trail->parent('tax.index');
    $trail->push('Create Tax', route('tax.create'));
});

Breadcrumbs::for('tax.edit', function (BreadcrumbTrail $trail, $tax) {
    $trail->parent('tax.index');
    $trail->push('Edit Tax', route('tax.edit', $tax));
});

// Customization
Breadcrumbs::for('customization.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Customization', route('customization.index'));
});

Breadcrumbs::for('customization.edit', function (BreadcrumbTrail $trail, $customization) {
    $trail->parent('customization.index');
    $trail->push('Edit Customization', route('customization.edit', $customization));
});

// Settings
Breadcrumbs::for('settings.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Settings', route('settings.edit'));
});

// System Settings
Breadcrumbs::for('systemSettings.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('System Settings', route('systemSettings.edit'));
});

// Account Management
Breadcrumbs::for('account.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Account', route('account.index'));
});

Breadcrumbs::for('account.update', function (BreadcrumbTrail $trail) {
    $trail->parent('account.index');
    $trail->push('Update Profile', route('account.update'));
});

Breadcrumbs::for('account.password', function (BreadcrumbTrail $trail) {
    $trail->parent('account.index');
    $trail->push('Change Password', route('account.password'));
});
