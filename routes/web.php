<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CloseProjectController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\ConversionRateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\FaqsCategoryController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\MappingController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Pages\TermAndConditionController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\SamplingController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SurveyInitController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\UserController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [WelcomeController::class, 'index']);

Route::get('login/{provider}', [SocialLoginController::class, 'redirectToGoogle']);
Route::get('login/{provider}/callback', [SocialLoginController::class, 'handleCallback']);

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name("dashboard");

    route::get('notifications', [NotificationController::class, 'index'])->name('notifications');

    Route::controller(CompanyController::class)->group(function () {
        Route::group(['prefix' => 'company'], function () {
            Route::get('', 'index')->name('company.index');
            Route::get('create', 'create')->name('company.create');
            Route::post('store', 'store')->name('company.store');
            Route::post('update', 'update')->name('company.update');
            Route::get('overview', 'overview')->name('company.overview');
            Route::get('addresses', 'addresses')->name('company.address');
            Route::post('address/create', 'addAddress')->name('company.addAddress');
            Route::post('address', 'updateAddress')->name('company.updateAddress');
            Route::delete('{id}/address', 'delAddress')->name('company.delAddress');

            Route::get('accounts', 'accounts')->name('company.accounts');
            Route::post('account/create', 'addAccount')->name('company.addAccount');
            Route::post('account', 'updateAccount')->name('company.updateAccount');
            Route::delete('{id}/account', 'delAccount')->name('company.delAccount');


            Route::get('redirects', 'redirects')->name('company.redirects');
            Route::post('redirect/create', 'addRedirect')->name('company.addRedirect');
            Route::post('redirect', 'updateRedirect')->name('company.updateRedirect');
            Route::delete('{id}/redirect', 'delRedirect')->name('company.delRedirect');
        });
    });

    Route::controller(ProjectController::class)->group(function () {
        //Project Index
        Route::get('projects', 'index')->name('projects');
        //Project Create
        Route::get('project/create', 'create')->name('project.create');

        //project state city

        Route::get('project/state', 'getState')->name('project.state');
        Route::get('project/city', 'getCity')->name('project.city');

        Route::post('project/store', 'store')->name('project.store');
        //Project Clone
        Route::post('project/clone', 'projectClone')->name('project.clone');
        //project Update
        Route::get('project/{id}/edit', 'edit')->name('project.edit');
        Route::post('project/update', 'update')->name('project.update');
        //Project Show
        Route::get('project/{id}', 'show')->name('project.show');

        Route::get('project/{id}/suppliers', 'suppliers')->name('project.suppliers');

        //activity
        Route::get('project/{id}/activity', 'activity')->name('project.activity');

        //question answer
        Route::get('project/{id}/question-answer', 'questionAnswer')->name('project.question-answer');
        Route::post('project/question-answer/store', 'storeQuestionAnswer')->name('project.question-answer.store');
        Route::get('project/question-answer/{id}/edit', 'editQuestionAnswer')->name('project.question-answer.edit');
        Route::post('project/question-answer/update', 'updateQuestionAnswer')->name('project.question-answer.update');
        Route::delete('project/question-answer/{id}', 'destroyQuestionAnswer')->name('project.question-answer.destroy');


        Route::delete('project/{id}', 'destroy')->name('project.destroy');
        Route::post('project/status', 'status')->name('project.status');

        Route::post('project/{id}/import', 'importId')->name('project.importid');
        Route::get('project/{id}/export', 'exportId')->name('project.exportid');
        Route::get('project/{id}/report', 'report')->name('project.report');
    });

    // Close projects
    Route::controller(CloseProjectController::class)->group(function () {

        Route::get('/close-projects', 'index')->name('close-projects');

        Route::post('close-project/restore', 'restore')->name('close-project.restore');

        Route::delete('close-project/destroy/{id}', 'destroy')->name('close-project.destroy');
    });

    Route::controller(MappingController::class)->group(function () {

        Route::get('mapping/{id}/create', 'create')->name('mapping.create');

        Route::post('mapping/{id}/store', 'store')->name('mapping.store');
        Route::get('mapping/{id}', 'show')->name('mapping.show');

        Route::get('mapping/{id}/edit', 'edit')->name('mapping.edit');
        Route::post('mapping/{id}/update', 'update')->name('mapping.update');

        Route::get('mapping/{id}/suppliers', 'suppliers')->name('mapping.suppliers');
        Route::get('project-link/{id}/suppliers', 'projectLinkSuppliers')->name('project-link.suppliers');

        Route::delete('mapping/{id}', 'destroy')->name('mapping.destroy');
        Route::post('mapping/status', 'status')->name('mapping.status');
        Route::post('mapping/sample-size', 'sampleSize')->name('mapping.sample-size');
    });

    Route::controller(SamplingController::class)->group(function () {
        Route::get('sampling/{id}/create', 'create')->name('sampling.create');
        Route::post('sampling/store', 'store')->name('sampling.store');

        Route::get('sampling/{id}/edit', 'edit')->name('sampling.edit');
        Route::post('sampling/{id}/update', 'update')->name('sampling.update');

        Route::get('sampling/{id}', 'show')->name('sampling.show');
        Route::delete('sampling/{id}', 'destroy')->name('sampling.destroy');
        Route::post('sampling/status', 'status')->name('sampling.status');
        Route::get('sampling/{id}/redirect', 'redirect')->name('sampling.redirect');
    });

    Route::controller(SupplierController::class)->group(function () {
        Route::get('suppliers', 'index')->name('suppliers.index');
        Route::get('supplier/create', 'create')->name('supplier.create');
        Route::post('supplier/store', 'store')->name('supplier.store');
        Route::get('supplier/{id}/edit', 'edit')->name('supplier.edit');
        Route::post('supplier/update', 'update')->name('supplier.update');
        Route::get('supplier/{id}', 'show')->name('supplier.show');
        Route::delete('supplier/{id}', 'destroy')->name('supplier.destroy');
        //supplier Project
        Route::get('supplier/{id}/projects', 'projects')->name('supplier.projects');
        //supplier Project
        Route::get('supplier/{id}/redirect', 'redirect')->name('supplier.redirect');
        //Respodents
        Route::get('supplier/{id}/respondents', 'respondents')->name('supplier.respondents');
        //Suppliers Reports
        Route::get('supplier/{id}/export', 'supplierExports')->name('supplier.export');

        Route::post('supplier/redirect/update', 'updateRedirect')->name('supplier.redirect.update');
        //Supplier Status
        Route::post('supplier/status', 'changeStatus')->name('supplier.status');
        // excel export
        Route::get('supplier/{id}/projects/export', 'exportIds')->name('supplier.projects.export');
    });
    Route::controller(ClientController::class)->group(
        function () {
            Route::get('clients', 'index')->name('client.index');
            Route::get('client/create', 'create')->name('client.create');
            Route::post('client/store', 'store')->name('client.store');
            Route::get('client/{id}', 'show')->name('client.show');
            Route::get('client/{id}/edit', 'edit')->name('client.edit');

            Route::post('client/{id}/update', 'update')->name('client.update');

            Route::post('client/status', 'status')->name('client.status');

            Route::delete('client/{id}/destroy', 'destroy')->name('client.destroy');
            Route::get('client/{id}/projects', 'projects')->name('client.projects');
            // Client Address
            Route::get('client/{id}/address', 'address')->name('client.address');
            Route::post('client/{id}/address', 'addUpdateAddress')->name('client.addUpdateAddress');
            Route::delete('client/{id}/address', 'delAddress')->name('client.delAddress');
            Route::get('client/{id}/invoice-address', 'getAddress')->name('client.invoice-address');

            // Client Contact
            Route::get('client/{id}/contact', 'contact')->name('client.contact');
            Route::post('client/{id}/contact', 'addUpdateContact')->name('client.addUpdateContact');
            Route::delete('client/{id}/contact', 'delContact')->name('client.delContact');
        }
    );

    Route::controller(InvoiceController::class)->group(function () {
        Route::get('/invoices', 'index')->name('invoice.index');
        Route::group(['prefix' => 'invoice'], function () {
            Route::get('create', 'create')->name('invoice.create');
            Route::post('/store', 'store')->name('invoice.store');
            Route::get('/{id}', 'show')->name('invoice.show');
            Route::get('{id}/edit', 'edit')->name('invoice.edit');
            Route::post('{id}/update', 'update')->name('invoice.update');
            Route::delete('destroy/{id}', 'destroy')->name('invoice.destroy');
        });
    });
    Route::controller(ConversionRateController::class)->group(function () {
        //
        Route::get('invoice/{id}/conversion-value', 'conversionValue')->name('invoice.conversion-value');
    });


    Route::controller(MessageController::class)->group(
        function () {
            Route::get('messaging', 'index')->name('message.index');
            Route::get('message/{id}', 'thread')->name('message.thread');
            Route::post('message/store', 'send')->name('message.store');
            Route::get('messaging/search', 'searchUsers')->name('message.search');
        }
    );

    Route::controller(ConversationController::class)->group(
        function () {
            Route::get('chat', 'index')->name('chat.index');
        }
    );
    Route::get('notifications/header', [NotificationController::class, 'headerNotifications']);

    Route::controller(AccountController::class)->group(function () {
        //User OverView
        Route::get('account/overview', 'overview')->name('account.overview');
        Route::post('account', 'store')->name('account.user.store');
        // User Address
        Route::get('account/address', 'address')->name('account.address');
        Route::post('account/address/update', 'updateAddress')->name('account.address.update');
        // User Settings
        Route::get('account/setting', 'setting')->name('account.setting');
        Route::post('account/email/update', 'emailUpdate')->name('account.email.update');
        Route::post('account/change-password', 'changePassword')->name('account.change-password');
        Route::post('account/deactivate', 'deactivate')->name('account.deactivate');
        //User Image
        Route::post('/avatar-upload', 'avatarImage');
    });
    Route::controller(UserController::class)->group(function () {
        Route::get('users', 'index')->name('users.index');
        Route::get('user/create', 'create')->name('user.create');
        Route::post('user/store', 'store')->name('user.store');
        Route::get('user/{id}/edit', 'edit')->name('user.edit');
        Route::post('user/{id}/update', 'update')->name('user.update');
        Route::get('user/{id}', 'show')->name('user.show');
        Route::delete('user/{id}', 'destroy')->name('user.destroy');
        // User Project
        Route::get('user/{id}/projects', 'projects')->name('user.projects');
        // User Setting
        Route::get('user/{id}/setting', 'setting')->name('user.setting');
        Route::post('user/addAddress/{id}', 'addAddress')->name('user.addAddress');
        Route::post('user/updateAddress/{id}', 'updateAddress')->name('user.updateAddress');
        Route::delete('user/delAddress/{id}', 'delAddress')->name('user.delAddress');
        // User Address
        Route::get('user/{id}/address', 'address')->name('user.address');
        Route::post('user/addAddress/{id}', 'addAddress')->name('user.addAddress');
        Route::post('user/updateAddress/{id}', 'updateAddress')->name('user.updateAddress');
        Route::delete('user/delAddress/{id}', 'delAddress')->name('user.delAddress');
        //Excel export
        Route::get('user-project/export', 'exportProjectIds')->name('user-project.report');
    });
    Route::controller(MenuController::class)->group(function () {
        Route::get('/menus', 'index')->name('menu.index');
        Route::group(['prefix' => 'menu'], function () {
            Route::get('/create', 'create')->name('menu.create');
            Route::post('/store', 'store')->name('menu.store');
            Route::get('{id}/edit', 'edit')->name('menu.edit');
            Route::post('{id}/update', 'update')->name('menu.update');
            Route::delete('delete/{id}', 'destroy')->name('menu.destroy');
        });
    });

    Route::controller(MenuItemController::class)->group(function () {
        Route::get('/menu-items', 'index')->name('menu-item.index');
        Route::group(['prefix' => 'menu-item'], function () {
            Route::get('/create', 'create')->name('menu-item.create');
            Route::post('/store', 'store')->name('menu-item.store');
            Route::get('{id}/edit', 'edit')->name('menu-item.edit');
            Route::post('{id}/update', 'update')->name('menu-item.update');
            Route::delete('{id}', 'destroy')->name('menu-item.destroy');
        });
    });

    Route::controller(EmployeeController::class)->group(function () {
        Route::get('/employees', 'index')->name('employee.index');
        Route::group(['prefix' => 'employee'], function () {
            Route::get('create', 'create')->name('employee.create');
            Route::get('{id}', 'overview')->name('employee.show');
            Route::post('store', 'store')->name('employee.store');
            Route::get('{id}/edit', 'edit')->name('employee.edit');
            Route::post('{id}/update', 'update')->name('employee.update');
            Route::delete('{id}', 'destroy')->name('employee.delete');
            Route::post('employees/delete', 'selectDelete')->name('employees.delete');
            //Employee OverView

            // Employee Address
            Route::get('{id}/address', 'address')->name('employee.address');
            Route::post('{id}/address/update', 'updateAddress')->name('employee.address.update');

            // Empoyee Security
            Route::get('{id}/security', 'security')->name('employee.security');
            Route::post('{id}/email', 'emailUpdate')->name('employee.updateEmail');
            Route::post('{id}/change-password', 'changePassword')->name('employee.changePassword');
            Route::post('{id}/deactivate', 'deactivate')->name('employee.deactivate');
            // Employee Settings
            Route::get('{id}/settings', 'setting')->name('employee.settings');

            // Employee Attendance
            Route::get('{id}/attendance', 'attendance')->name('employee.attendance');
        });
    });

    Route::controller(PlanController::class)->group(function () {
        Route::get('plans',  'index')->name('plans.index');
        Route::group(['prefix' => 'plan'], function () {
            Route::get('create', 'create')->name('plan.create');
            Route::post('store', 'store')->name('plan.store');
            Route::get('{id}',  'show')->name('plan.show');
            Route::get('{id}/edit',  'edit')->name('plan.edit');
            Route::post('update', 'update')->name('plan.update');
            Route::delete('destroy/{id}', 'destroy')->name('plan.destroy');

            // transaction
            Route::post('/subscription', 'subscription')->name('plan.subscription');
            Route::get('users',  'getUser')->name('plan.users');
            Route::get('page/error', 'errorPage')->name('plan.error');
        });
    });

    Route::controller(SubscriptionController::class)->group(function () {
        Route::get('subscriptions', 'index')->name('subscription.index');
        Route::prefix('subscription')->group(function () {
            Route::get('{id}', 'show')->name('subscription.show');
            Route::post('checkout', 'checkout')->name('subscription.checkout');
            Route::get('plan-update/{id}', 'getPlan')->name('subscription.plan-update');
            Route::post('update', 'updatePlan')->name('subscription.update');
            Route::post('cancel', 'cancelSubscription')->name('subscription.cancel');
        });
        Route::prefix('payment')->group(function () {
            Route::get('success', 'successPage')->name('payment.success');
            Route::get('cancel', 'cancelPage')->name('payment.cancel');
        });
    });
    Route::post('webhook', [SubscriptionController::class, 'webhook']);
    Route::controller(ImageController::class)->group(function () {
        Route::post('/image/{entity}', 'store')->name('image.store');
    });

    Route::controller(SupportController::class)->group(function () {
        // Support  overview
        Route::get('supports', 'show')->name('supports.show');
        Route::group(['prefix' => 'support'], function () {
            Route::get('/create', 'create')->name('support.create');
            Route::post('/store', 'store')->name('support.store');
            Route::get('/edit', 'edit')->name('support.edit');
            Route::put('/update', 'update')->name('support.update');
            Route::delete('/delete', 'destroy')->name('support.delete');
            // Support tickets
            Route::get('tickets', 'tickets')->name('support.tickets');
            Route::get('{id}/view-tickets', 'viewTickets')->name('support.view-tickets');
            // Support  tutorials
            Route::get('tutorials', 'tutorials')->name('support.tutorials');
            Route::get('view-tutorials', 'viewTutorials')->name('support.view-tutorials');
            // Support faq
            Route::get('/faq', 'faq')->name('support.faq');
            // Support licenses
            Route::get('/licenses', 'licenses')->name('support.licenses');
            // Support contact-us
            Route::get('/contact-us', 'contactUs')->name('support.contact-us');
            Route::post('/store/contact-us', 'contactUsStore')->name('support.store.contact-us');
        });
    });

    Route::controller(PageController::class)->group(function () {
        // Support  overview
        Route::get('pages', 'index')->name('pages.index');
        Route::group(['prefix' => 'page'], function () {
            Route::get('/terms-and-condition', 'index')->name('page.terms-and-condition');
            Route::post('/terms-and-condition', 'termAndCondition')->name('page.terms-and-condition.store');
            // page tickets
            Route::get('privacy-policy', 'getPrivacyPolicy')->name('page.privacy-policy');
            Route::post('privacy-policy', 'storePrivacyPolicy')->name('page.privacy-policy.store');
            // page  tutorials
            Route::get('refund-policy', 'getRefundPolicy')->name('page.refund-policy');
            Route::post('refund-policy', 'storeRefundPolicy')->name('page.refund-policy.store');
            // page faq
            Route::get('/return-policy', 'getReturnPolicy')->name('page.return-policy');
            Route::post('/return-policy', 'storeReturnPolicy')->name('page.return-policy.store');
            // page licenses
            Route::get('/cancellation-policy', 'getCancellationPolicy')->name('page.cancellation-policy');
            Route::post('/cancellation-policy', 'storeCancellationPolicy')->name('page.cancellation-policy.store');
            // page about-us
            Route::get('/about-us', 'getAboutUs')->name('page.about-us');
            Route::post('/about-us', 'storeAboutUs')->name('page.about-us.store');
        });
    });

    Route::controller(ServiceController::class)->group(function () {
        Route::get('/services', 'index')->name('services.index');
        Route::group(['prefix' => 'service'], function () {
            Route::get('/create', 'create')->name('service.create');
            Route::post('/store', 'store')->name('service.store');
            Route::get('{id}', 'show')->name('service.show');
            Route::get('{id}/edit', 'edit')->name('service.edit');
            Route::post('update', 'update')->name('service.update');
            Route::delete('delete/{id}', 'destroy')->name('service.destroy');
        });
    });
    Route::controller(TestimonialController::class)->group(function () {
        Route::get('/testimonials', 'index')->name('testimonials.index');
        Route::group(['prefix' => 'testimonial'], function () {
            Route::post('/status', 'statusUpdate')->name('testimonial.status');
            Route::get('/create', 'create')->name('testimonial.create');
            Route::post('/store', 'store')->name('testimonial.store');
            Route::get('{id}', 'show')->name('testimonial.show');
            Route::get('{id}/edit', 'edit')->name('testimonial.edit');
            Route::post('{id}/update', 'update')->name('testimonial.update');
            Route::delete('delete/{id}', 'destroy')->name('testimonial.destroy');
        });
    });
    Route::controller(BlogController::class)->group(function () {
        Route::get('/blogs', 'index')->name('blogs.index');
        Route::group(['prefix' => 'blog'], function () {
            Route::get('/create', 'create')->name('blog.create');
            Route::post('/store', 'store')->name('blog.store');
            Route::post('/status', 'statusUpdate')->name('blog.status');
            Route::get('{id}/edit', 'edit')->name('blog.edit');
            Route::get('{id}', 'show')->name('blog.show');
            Route::post('{id}/update', 'update')->name('blog.update');
            Route::delete('delete/{id}', 'destroy')->name('blog.destroy');
        });
    });
    Route::controller(FAQController::class)->group(function () {
        Route::get('/faqs', 'index')->name('faqs.index');
        Route::group(['prefix' => 'faq'], function () {
            Route::get('/create', 'create')->name('faq.create');
            Route::post('/store', 'store')->name('faq.store');
            Route::post('/status', 'status')->name('faq.status');
            Route::get('{id}/edit', 'edit')->name('faq.edit');
            Route::post('{id}/update', 'update')->name('faq.update');
            Route::delete('delete/{id}', 'destroy')->name('faq.destroy');
        });
    });


    Route::controller(FaqsCategoryController::class)->group(function () {
        Route::get('faqs-categories', 'index')->name('faqs-categories.index');
        Route::group(['prefix' => 'faq-category'], function () {
            Route::post('status', 'statusUdate')->name('faq-category.status');
            Route::get('/create', 'create')->name('faq-category.create');
            Route::post('/store', 'store')->name('faq-category.store');
            Route::get('{id}', 'show')->name('faq-category.show');
            Route::get('{id}/edit', 'edit')->name('faq-category.edit');
            Route::post('update', 'update')->name('faq-category.update');
            Route::delete('{id}/destroy', 'destroy')->name('faq-category.destroy');
        });

        Route::post('support/faq/create', 'store')->name('support.faq.create');
        Route::post('support/faq/update', 'update')->name('support.faq.update');
    });

    Route::controller(RoleController::class)->group(function () {
        Route::group(['prefix' => 'role'], function () {
            Route::get('/', 'index')->name('role.index');
            Route::post('/store', 'store')->name('role.store');
            Route::get('{id}/edit', 'edit')->name('role.edit');
            Route::get('{id}', 'show')->name('role.show');
            Route::post('{id}/update', 'update')->name('role.update');
            Route::delete('delete/{id}', 'destroy')->name('role.destroy');
        });
    });

    Route::controller(PermissionController::class)->group(function () {
        Route::get('/permissions', 'index')->name('permission.index');
        Route::group(['prefix' => 'permission'], function () {
            Route::post('/store', 'store')->name('permission.store');
            Route::get('{id}/edit', 'edit')->name('permission.edit');
            Route::post('{id}/update', 'update')->name('permission.update');
            Route::delete('delete/{id}', 'destroy')->name('permission.destroy');
        });
    });
});

Route::get('surveyRoute/{id}', [SurveyInitController::class, 'questionTest'])->name("survey.start");
Route::post('answer-check', [SurveyInitController::class, 'checkAnswer'])->name("survey.answer-check");

// Route::get('surveyRoute/{id}', [SurveyInitController::class, 'start'])->name("survey.start");

Route::get('surveyEnd/{id}', [RedirectController::class, 'status'])->name("survey.status");

Route::get('success', function () {
    return Inertia::render('Plan/Page/Success');
});
