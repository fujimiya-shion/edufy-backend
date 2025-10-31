<?php

namespace App\Providers;

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
use App\Services\Contracts\Account\IAdminRepository;
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
use Illuminate\Support\ServiceProvider;

class CRUDServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Repositories
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

        // Services
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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
