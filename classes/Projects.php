<?php
declare(strict_types=1);

class Projects
{
    public function getProjects(array $request): array
    {
        // Example data
        return [
            ['id' => 1, 'name' => 'Project Alpha', 'status' => 'active'],
            ['id' => 2, 'name' => 'Project Beta', 'status' => 'draft'],
        ];
    }

    public function editProject(array $request): array
    {
        $projectId = isset($request['project_id']) ? (int)$request['project_id'] : 0;

        if ($projectId < 1) {
            throw new Exception('Invalid project_id');
        }

        // Normally fetch from DB here
        return [
            'id'          => $projectId,
            'name'        => 'Sample Project',
            'description' => 'Loaded for editing',
            'status'      => 'active',
        ];
    }

    public function saveProject(array $request): array
    {
        $projectId   = isset($request['project_id']) ? (int)$request['project_id'] : 0;
        $projectName = trim($request['project_name'] ?? '');

        if ($projectName === '') {
            throw new Exception('Project name is required');
        }

        // Normally insert/update DB here
        return [
            'message'    => 'Project saved successfully',
            'project_id' => $projectId ?: 123,
            'name'       => $projectName,
        ];
    }

    public function deleteProject(array $request): array
    {
        $projectId = isset($request['project_id']) ? (int)$request['project_id'] : 0;

        if ($projectId < 1) {
            throw new Exception('Invalid project_id');
        }

        // Normally delete from DB here
        return [
            'message'    => 'Project deleted successfully',
            'project_id' => $projectId,
        ];
    }
}