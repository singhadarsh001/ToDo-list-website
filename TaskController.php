namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // Fetch all tasks
    public function index()
    {
        $tasks = Task::all();
        return view('tasks.index', compact('tasks'));
    }

    // Store a new task
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task' => 'required|unique:tasks,task'
        ]);

        Task::create([
            'task' => $request->task,
        ]);

        return response()->json(['message' => 'Task added successfully']);
    }

    // Update task to completed
    public function update($id)
    {
        $task = Task::find($id);
        $task->completed = !$task->completed;
        $task->save();

        return response()->json(['message' => 'Task updated successfully']);
    }

    // Delete a task
    public function destroy($id)
    {
        $task = Task::find($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
