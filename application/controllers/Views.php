<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Views extends Application
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/
     *     - or -
     *         http://example.com/welcome/index
     *
     * So any other public methods not prefixed with an underscore will
     * map to /welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

    public function index()
    {
        $this->data['pagetitle'] = 'Ordered TODO List';
        $tasks = $this->tasks->all();   // get all the tasks
        $this->data['content'] = 'Ok'; // so we don't need pagebody
        $this->data['leftside'] = $this->makePrioritizedPanel($tasks);
        $this->data['rightside'] = $this->makeCategorizedPanel();

        $this->render('template_secondary');
    }

    function makePrioritizedPanel($tasks)
    {
        foreach ($tasks as $task)
        {
            if ($task->status != 2)
                $undone[] = $task;
        }
        usort($undone, 'orderByPriority');

        foreach ($undone as $task)
        {
            $task->priority = $this->app->priority($task->priority);
            $converted[] = (array) $task;
        }

        $parms = ['display_tasks' => $converted];
        return $this->parser->parse('by_priority', $parms, true);
    }


    function makeCategorizedPanel()
    {
        $params = ['display_tasks' => $this->tasks->getCategorizedTasks()];
        return $this->parser->parse('by_category', $params, true);
    }
}

function orderByPriority($a, $b)
{
    if ($a->priority > $b->priority)
        return -1;
    elseif ($a->priority < $b->priority)
        return 1;
    else
        return 0;
}
