<?php


namespace LMS\Courses\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LMS\Courses\Repositories\CourseRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use LMS\Courses\Http\Requests\CreateCourseRequest;

class CoursesController extends Controller
{
    private CourseRepository $repository;

    public function __construct(CourseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function postCourse(CreateCourseRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $data = $this->repository->create($payload);

        return response()->json($data, Response::HTTP_CREATED);
    }

    public function deleteCourse(Request $request, int $course): JsonResponse
    {
        $this->validate($request->merge(['id' => $course]), ['id' => 'exists:courses,id']);

        $deleted = $this->repository->delete($course);
        return response()->json($deleted, $deleted
                ? Response::HTTP_NO_CONTENT
                : Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
