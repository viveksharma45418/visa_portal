<?php
/**
 * ApplicationController — Handles the public-facing application form.
 */
class ApplicationController
{
    private ApplicationModel $model;

    public function __construct(ApplicationModel $model)
    {
        $this->model = $model;
    }

    /**
     * Display the multi-step application form.
     */
    public function showApplicationForm(): void
    {
        include BASE_PATH . '/views/apply.php';
    }
}
