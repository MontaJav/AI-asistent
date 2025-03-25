## Author: Monta Javnošāne, Amanda Vītola

### Description
This is a simple web application for students to manage their courses, lessons and assignments.\
It includes a chatbot Assistant, which can help with planning your schedule,\
checking deadlines and answering questions about your courses.

Most of the code is not AI-powered and can be used manually, 
but the Assistant is powered by OpenAI API and can read and write into the database.

### Functionality
- As an authorized user, you have access to the list of courses, lessons and assignments
- You can register and unregister into courses in the Courses tab, thus making assignments and lessons actual for you
- You can see the lessons and assignment deadlines in the Calendar tab
- You can plan your personal syllabus in the My Schedule tab
- You can use the Chat tab to ask the Assistant for help with your current courses and assignments, check your schedule and add items there

### Technical info to keep in mind
- The Assistant instructions and tools can be seen in the code in the `Assistant.php` file, and some of them are tested, but currently commented, since they use too much context, and the chatbot quickly runs out of tokens
- The Courses, Lessons and Assignments are generated randomly upon installation with Laravel factories (`CourseFactory.php`, `LessonFactory.php`, `AssignmentFactory.php`) and there is no functionality to edit them, only to register and unregister into courses and complete assignments with random grades
- The `.env` file has several important configuration keys:
  - `OPENAI_API_KEY` - the key to access the OpenAI API
  - `STUDENT_NAME`, `STUDENT_EMAIL` and `STUDENT_PASSWORD` - the credentials used to create a user for logging in
  - `MAX_CHAT_MESSAGES` - the maximum number of messages that get passed to the Assistant in one request as a context

### General info
- Framework: [Laravel](https://laravel.com/)
- Frontend: Blade
- Database: SQLite
- AI API: [OpenAI PHP Client](https://github.com/openai-php/client)

### Installation
- fill `OPENAI_API_KEY` in .env file
- fill `STUDENT_NAME`, `STUDENT_EMAIL` and `STUDENT_PASSWORD` in .env file
- fill `DB_` keys in .env file to match your local database
- `composer install`
- `php artisan key:generate`
- `php artisan migrate`
- `php artisan db:seed`
- `php artisan serve`
- `npm install && npm run build`
- `composer run dev`

(and just `php artisan serve` after to run after the first installation)

### Screenshots
- Assistant getting the data from the database
  - [Assistant lists courses](/screens/courses.png)
  - [Assistant lists user's assignments](/screens/assignments.png)
  - [Assistant answers user's question about a course](/screens/ask_about_courses.png)
- Assistant adding new data:
  - [Assistant lists user's schedule and adds new item on prompt](/screens/schedule.png)
  - [User's schedule after adding a new item](/screens/altered_schedule_result.png)
- Assistant offering a personalized study plan:
  - [Assistant analizes user's deadlines](/screens/study_plan.png)

### Architecture
[Diagram](/architecture.jpg)

### Ideas for future updates
- Add user roles to be able to log in as a teacher and edit courses, lessons and assignments
- Add the option to export the schedule to a calendar
- Add the option to edit the schedule
- Add the option to export the bot chat history
- Add a cron that would automatically send reminders about deadlines
- Add sync from 3rd party calendars (Google Calendar, etc.)

[Home](/)
