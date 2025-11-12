<?php

namespace App\Providers;

use App\Repositories\Contracts\Cart\ICartItemRepository;
use App\Repositories\Contracts\Cart\ICartRepository;
use App\Repositories\Implementations\Cart\CartItemRepository;
use App\Repositories\Implementations\Cart\CartRepository;
use App\Services\Contracts\Account\IAdminRepository;
use App\Services\Contracts\Cart\ICartItemService;
use App\Services\Contracts\Cart\ICartService;
use App\Services\Implementations\Cart\CartItemService;
use App\Services\Implementations\Cart\CartService;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\Account\IUserRepository;
use App\Repositories\Contracts\Course\ICourseMediaRepository;
use App\Repositories\Contracts\Course\ICourseRepository;
use App\Repositories\Contracts\Course\ICourseScheduleRepository;
use App\Repositories\Contracts\Lesson\ILessonMediaRepository;
use App\Repositories\Contracts\Lesson\ILessonRepository;
use App\Repositories\Contracts\Ribbon\IRibbonItemRepository;
use App\Repositories\Contracts\Ribbon\IRibbonRepository;
use App\Repositories\Contracts\Role\IRoleRepository;
use App\Repositories\Contracts\Teacher\ITeacherRepository;
use App\Repositories\Contracts\TrainingCenter\ITrainingCenterRepository;
use App\Repositories\Contracts\Order\IOrderRepository;
use App\Repositories\Contracts\Order\IOrderItemRepository;
use App\Repositories\Contracts\Payment\IPaymentRepository;
use App\Repositories\Contracts\Payment\IPaymentLogRepository;
use App\Repositories\Implementations\Account\AdminRepository;
use App\Repositories\Implementations\Account\UserRepository;
use App\Repositories\Implementations\Course\CourseMediaRepository;
use App\Repositories\Implementations\Course\CourseRepository;
use App\Repositories\Implementations\Course\CourseScheduleRepository;
use App\Repositories\Implementations\Lesson\LessonMediaRepository;
use App\Repositories\Implementations\Lesson\LessonRepository;
use App\Repositories\Implementations\Ribbon\RibbonItemRepository;
use App\Repositories\Implementations\Ribbon\RibbonRepository;
use App\Repositories\Implementations\Role\RoleRepository;
use App\Repositories\Implementations\Teacher\TeacherRepository;
use App\Repositories\Implementations\TrainingCenter\TrainingCenterRepository;
use App\Repositories\Implementations\Order\OrderRepository;
use App\Repositories\Implementations\Order\OrderItemRepository;
use App\Repositories\Implementations\Payment\PaymentRepository;
use App\Repositories\Implementations\Payment\PaymentLogRepository;
use App\Services\Contracts\Account\IAdminService;
use App\Services\Contracts\Account\IUserService;
use App\Services\Contracts\Course\ICourseMediaService;
use App\Services\Contracts\Course\ICourseScheduleService;
use App\Services\Contracts\Course\ICourseService;
use App\Services\Contracts\Lesson\ILessonMediaService;
use App\Services\Contracts\Lesson\ILessonService;
use App\Services\Contracts\Ribbon\IRibbonItemService;
use App\Services\Contracts\Ribbon\IRibbonService;
use App\Services\Contracts\Role\IRoleService;
use App\Services\Contracts\Teacher\ITeacherService;
use App\Services\Contracts\TrainingCenter\ITrainingCenterService;
use App\Services\Contracts\Order\IOrderService;
use App\Services\Contracts\Payment\IPaymentService;
use App\Services\Contracts\Payment\IPaymentLogService;
use App\Services\Implementations\Account\AdminService;
use App\Services\Implementations\Account\UserService;
use App\Services\Implementations\Course\CourseMediaService;
use App\Services\Implementations\Course\CourseScheduleService;
use App\Services\Implementations\Course\CourseService;
use App\Services\Implementations\Lesson\LessonMediaService;
use App\Services\Implementations\Lesson\LessonService;
use App\Services\Implementations\Ribbon\RibbonItemService;
use App\Services\Implementations\Ribbon\RibbonService;
use App\Services\Implementations\Role\RoleService;
use App\Services\Implementations\Teacher\TeacherService;
use App\Services\Implementations\TrainingCenter\TrainingCenterService;
use App\Services\Implementations\Order\OrderService;
use App\Services\Implementations\Payment\PaymentService;
use App\Services\Implementations\Payment\PaymentLogService;

class CRUDServiceProvider extends ServiceProvider
{
    public function register(): void {
        $this->app->bind(IAdminRepository::class, AdminRepository::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(ICourseRepository::class, CourseRepository::class);
        $this->app->bind(ICourseMediaRepository::class, CourseMediaRepository::class);
        $this->app->bind(ICourseScheduleRepository::class, CourseScheduleRepository::class);
        $this->app->bind(ILessonRepository::class, LessonRepository::class);
        $this->app->bind(ILessonMediaRepository::class, LessonMediaRepository::class);
        $this->app->bind(IRoleRepository::class, RoleRepository::class);
        $this->app->bind(ITeacherRepository::class, TeacherRepository::class);
        $this->app->bind(ITrainingCenterRepository::class, TrainingCenterRepository::class);
        $this->app->bind(IRibbonRepository::class, RibbonRepository::class);
        $this->app->bind(IRibbonItemRepository::class, RibbonItemRepository::class);
        $this->app->bind(IOrderRepository::class, OrderRepository::class);
        $this->app->bind(IOrderItemRepository::class, OrderItemRepository::class);
        $this->app->bind(IPaymentRepository::class, PaymentRepository::class);
        $this->app->bind(IPaymentLogRepository::class, PaymentLogRepository::class);
        $this->app->bind(ICartRepository::class, CartRepository::class);
        $this->app->bind(ICartItemRepository::class, CartItemRepository::class);

        $this->app->bind(IAdminService::class, AdminService::class);
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(ICourseService::class, CourseService::class);
        $this->app->bind(ICourseMediaService::class, CourseMediaService::class);
        $this->app->bind(ICourseScheduleService::class, CourseScheduleService::class);
        $this->app->bind(ILessonService::class, LessonService::class);
        $this->app->bind(ILessonMediaService::class, LessonMediaService::class);
        $this->app->bind(IRoleService::class, RoleService::class);
        $this->app->bind(ITeacherService::class, TeacherService::class);
        $this->app->bind(ITrainingCenterService::class, TrainingCenterService::class);
        $this->app->bind(IRibbonService::class, RibbonService::class);
        $this->app->bind(IRibbonItemService::class, RibbonItemService::class);
        $this->app->bind(IOrderService::class, OrderService::class);
        $this->app->bind(IPaymentService::class, PaymentService::class);
        $this->app->bind(IPaymentLogService::class, PaymentLogService::class);
        $this->app->bind(ICartService::class, CartService::class);
        $this->app->bind(ICartItemService::class, CartItemService::class);
    }

    public function boot(): void {
    }
}
