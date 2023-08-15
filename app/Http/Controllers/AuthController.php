<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Project;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
   public function construct(){
    $this->middleware('auth:api',['except'=>['login','register']]);
   }
   //-----------------------REGISTER----------------------------------------------------------
    public function register(Request $request){
$validator = Validator::make($request->all(),[
    'name'=>'required',
    'email'=>'required|string|email|unique:users',
    'password'=>'required|string|confirmed|min:6'
]);
if($validator->fails()){
    return response()->json($validator->errors()->toJson(),400);
}
$user = User::create(array_merge($validator->validated(),
['password'=>bcrypt($request->password)]
));
return response()->json([
    "message"=>"user registration successful",
    'user'=>$user
],201);
//---------------------------------------------------------------------------------
//-----------------------LOGIN----------------------------------------------------------
   }
   public function login(Request $request){
    $validator = Validator::make($request->all(),[
        
        'email'=>'required|email',
        'password'=>'required|string|min:6'
    ]);
    if($validator->fails()){
        return response()->json($validator->errors(),422);
    }
    if(!$token=auth()->attempt($validator->validated())){
    return response()->json(['error'=>'Unauthorized'],401);
    }
    return $this->createNewToken($token);
   }
   public function createNewToken($token){
     return response()->json([
        'access_token'=>$token,
        'token_type'=>'bearer',
        'expires'=>auth::factory()->getTTL()*60,
        'user'=>auth()->user()
     ]);
   }
   //---------------------------------------------------------------------------------
//-----------------------view PROFILE----------------------------------------------------------
   public function profile(){
return response()->json(auth()->user());
   }
//---------------------------------------------------------------------------------
//-----------------------LOGOUT----------------------------------------------------------   
public function logout(){
auth()->logout();
return response()->json([
    "message"=>"user logged out successful"
],201);
   }
//---------------------------------------------------------------------------------
//-----------------------ADD CATEGORY----------------------------------------------------------

   public function addCategory(Request $request)
   {
    if (!auth()->check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
       $validator = Validator::make($request->all(), [
           'name' => 'required',
           'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
           'description' => 'required', // Adjust the allowed image types and maximum size as needed
       ]);
   
       if ($validator->fails()) {
           return response()->json(['error' => $validator->errors()], 422);
       }
   
       $category = new Category();
       $category->name = $request->input('name');
       $category->description = $request->input('description');
   
       if ($request->hasFile('photo')) {
           $file = $request->file('photo');
           $extension = $file->getClientOriginalExtension();
           $fileName = time() . '.' . $extension;
           $file->move('storage/category', $fileName);
           $category->photo = $fileName;
       }
   
       $category->save();
   
       return response()->json(['message' => 'Category added successfully'], 201);
   }
   //---------------------------------------------------------------------------------
   //-----------------------view CATEGORY----------------------------------------------------------
   public function viewCategory(){
    $categories = Category::all();
    return response()->json(['categories' => $categories], 200);
   }
   //---------------------------------------------------------------------------------
   //-----------------------UPDATE CATEGORY----------------------------------------------------------

   public function updateCategory(Request $request, $categoryId)
{
    // Check if the user is authenticated
    if (!auth()->check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $category = Category::find($categoryId);

    if (!$category) {
        return response()->json(['error' => 'Category not found'], 404);
    }

    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        'description' => 'required', // A
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    $category->name = $request->input('name');
    $category->description = $request->input('description');

    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '.' . $extension;
        $file->move('storage/category', $fileName);
        $category->photo = $fileName;
    }

    $category->save();

    return response()->json(['message' => 'Category updated successfully'], 200);
}
//---------------------------------------------------------------------------------
//-----------------------DELETE CATEGORY----------------------------------------------------------

public function deleteCategory($categoryId)
{
    // Check if the user is authenticated
    if (!auth()->check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $category = Category::find($categoryId);

    if (!$category) {
        return response()->json(['error' => 'Category not found'], 404);
    }

    $category->delete();

    return response()->json(['message' => 'Category deleted successfully'], 200);
}
//---------------------------------------------------------------------------------

// create testimonials function

public function createTestimonials(Request $request)
{
 if (!auth()->check()) {
     return response()->json(['error' => 'Unauthorized'], 401);
 }
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'review' => 'required', // Adjust the allowed image types and maximum size as needed
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    $category = new Testimonial();
    $category->name = $request->input('name');
    $category->review = $request->input('review');

    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '.' . $extension;
        $file->move('storage/testimonials', $fileName);
        $category->photo = $fileName;
    }

    $category->save();

    return response()->json(['message' => 'testimonial added successfully'], 201);
}

// view testimonials function

public function viewTestimonial(){
    $testimonials = Testimonial::all();
    return response()->json(['testimonials' => $testimonials], 200);
   }

   public function updateTestimonials(Request $request, $testimonialsId)
   {
       // Check if the user is authenticated
       if (!auth()->check()) {
           return response()->json(['error' => 'Unauthorized'], 401);
       }
   
       $testimonial = Testimonial::find($testimonialsId);
   
       if (!$testimonial) {
           return response()->json(['error' => 'Category not found'], 404);
       }
   
       $validator = Validator::make($request->all(), [
           'name' => 'required',
           'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
           'review' => 'required', // A
       ]);
   
       if ($validator->fails()) {
           return response()->json(['error' => $validator->errors()], 422);
       }
   
       $testimonial->name = $request->input('name');
       $testimonial->review = $request->input('review');
   
       if ($request->hasFile('photo')) {
           $file = $request->file('photo');
           $extension = $file->getClientOriginalExtension();
           $fileName = time() . '.' . $extension;
           $file->move('storage/testimonials', $fileName);
           $testimonial->photo = $fileName;
       }
   
       $testimonial->save();
   
       return response()->json(['message' => 'testimonial updated successfully'], 200);
   }
   
   public function deleteTestimonials($testimonialsId)
   {
       // Check if the user is authenticated
       if (!auth()->check()) {
           return response()->json(['error' => 'Unauthorized'], 401);
       }
   
       $category = Testimonial::find($testimonialsId);
   
       if (!$category) {
           return response()->json(['error' => 'testimonial not found'], 404);
       }
   
       $category->delete();
   
       return response()->json(['message' => 'testimonial deleted successfully'], 200);
   }
   
//projects functions

public function createProject(Request $request)
{
    if (!auth()->check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'description' => 'required',
        'category_id' => 'required',
        'skills' => 'array',
        'project_link' => 'nullable|url',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    $project = new Project;
    $project->name = $request->input('name');
    $project->description = $request->input('description');
    $project->category_id = $request->input('category_id');
    $project->skills = json_encode($request->input('skills'));
    $project->project_link = $request->input('project_link');

    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '.' . $extension;
        $file->move('storage/project', $fileName);
        $project->photo = $fileName;
    }

    // Save multiple project images if available
    if ($request->hasFile('images')) {
        $images = [];
        foreach ($request->file('images') as $image) {
            $imageName = $image->getClientOriginalName();
            $image->move('storage/project/projectimage', $imageName);
            $images[] = $imageName;
        }
        $project->images = json_encode($images);
    }

    $project->save();

    return response()->json(['message' => 'Project added successfully'], 201);
}
//-----------------------view project----------------------------------------------------------
public function viewProject()
{
    $projects = Project::all();
    return response()->json(['projects' => $projects], 200);
}
//---------------------------------------------------------------------------------
//-----------------------update project----------------------------------------------------------
public function updateProject(Request $request, $projectId)
{
    if (!auth()->check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $project = Project::find($projectId);

    if (!$project) {
        return response()->json(['error' => 'Project not found'], 404);
    }

    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        'description' => 'required',
        'category_id' => 'required',
        'skills' => 'array',
        'project_link' => 'nullable|url',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    $project->name = $request->input('name');
    $project->description = $request->input('description');
    $project->category_id = $request->input('category_id');
    $project->skills = json_encode($request->input('skills'));
    $project->project_link = $request->input('project_link');

    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '.' . $extension;
        $file->move('storage/project', $fileName);
        $project->photo = $fileName;
    }

    // Update multiple project images if available
    if ($request->hasFile('images')) {
        $images = [];
        foreach ($request->file('images') as $image) {
            $imageName = $image->getClientOriginalName();
            $image->move('storage/project/projectimage', $imageName);
            $images[] = $imageName;
        }
        $project->images = json_encode($images);
    }

    $project->save();

    return response()->json(['message' => 'Project updated successfully'], 200);
}
//---------------------------------------------------------------------------------
public function deleteProject($projectId)
{
    if (!auth()->check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $project = Project::find($projectId);

    if (!$project) {
        return response()->json(['error' => 'Project not found'], 404);
    }

    $project->delete();

    return response()->json(['message' => 'Project deleted successfully'], 200);
}
public function contactus(Request $request)
   {
    // if (!auth()->check()) {
    //     return response()->json(['error' => 'Unauthorized'], 401);
    // }
       $validator = Validator::make($request->all(), [
        //    'name' => 'required',
           'name' => 'required',
        'email' => 'required|email',
        'mobile' => 'required',
        'subject' => 'required',
        'message' => 'required',
       
        //    'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        //    'description' => 'required', // Adjust the allowed image types and maximum size as needed
       ]);
   
       if ($validator->fails()) {
           return response()->json(['error' => $validator->errors()], 422);
       }
   
       $category = new Contact();
       $category->name = $request->input('name');
       $category->email = $request->input('email');
       $category->mobile = $request->input('mobile');
       $category->subject = $request->input('subject');
       $category->message = $request->input('message');
       $category->save();
   
       return response()->json(['message' => 'Contact added successfully'], 201);
   }

   public function viewcontact()
   {
       $contacts = Contact::all();
       return response()->json(['contacts' => $contacts], 200);
   }

}
