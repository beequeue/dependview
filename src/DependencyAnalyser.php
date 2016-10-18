<?php

namespace Beequeue\DependView;

use Beequeue\DependView\Project\ProjectFactory;
use Beequeue\DependView\Table\{DependencyTable,Cell,Row};

class DependencyAnalyser
{
    protected $projects = [];

    protected $cacheDir;

    public function __construct($options = [])
    {
        $this->cacheDir = $options['cacheDir'];

        $this->parseProjects($options['projects']);

    }

    private function parseProjects($projectConfigs)
    {
        $projectFactory = new ProjectFactory([
            'cacheDir' => $this->cacheDir
        ]);

        foreach ($projectConfigs as $projectConfig) {
            $project = $projectFactory->create($projectConfig);
            $this->projects[$projectConfig['id']] = $project;
        }
    }

    public function getProjects()
    {
        return $this->projects;
    }

    public function analyse()
    {
        $byManagerAndDepdendency = $this->analyseByManagerAndDependency();

        $table = new DependencyTable();

        $headerRow = new Row();
        $headerRow->addCell(new Cell(['isHeader' => true]));
        foreach ($this->projects as $project) {
            $headerRow->addCell(new Cell([
                'isHeader' => true,
                'text' => $project->getLabel()
            ]));
        }
        $table->addRow($headerRow);

        $projectCount = count($this->projects);
        foreach ($byManagerAndDepdendency as $managerId => $dependencyGroups) {

            $row = new Row();
            $row->addCell(new Cell([
                'text' => $managerId,
                'isHeader' => true,
                'colSpan' => $projectCount + 1
            ]));
            $table->addRow($row);

            foreach ($dependencyGroups as $type => $dependencies) {

                $row = new Row();
                $row->addCell(new Cell([
                    'text' => $type,
                    'colSpan' => $projectCount + 1
                ]));
                $table->addRow($row);

                foreach ($dependencies as $dependency => $projects) {

                    $row = new Row();
                    $row->addCell(new Cell([
                        'text' => $dependency
                    ]));

                    foreach (array_keys($this->projects) as $projectId) {
                        if (isset($projects[$projectId])) {
                            $cell = new Cell([
                                'text' => $projects[$projectId]
                            ]);
                        } else {
                            $cell = new Cell();
                        }

                        $row->addCell($cell);
                    }

                    $table->addRow($row);
                }
            }
        }

        return $table;
    }

    private function analyseByManagerAndDependency(): array
    {
        $data = [];

        foreach ($this->projects as $project) {

            $projectId = $project->getId();

            foreach ($project->getDependencyManagers() as $manager) {

                $dependencyGroups = $manager->getProjectDependencies();
                if ($dependencyGroups) {

                    $managerId = $manager->getId();
                    if (!isset($data[$managerId])) {
                        $data[$managerId] = [];
                    }

                    foreach ($dependencyGroups as $type => $dependencies) {
                        if (!isset($data[$managerId][$type])) {
                            $data[$managerId][$type] = [];
                        }

                        foreach ($dependencies as $dependency => $version) {
                            if (!isset($data[$managerId][$type][$dependency])) {
                                $data[$managerId][$type][$dependency] = [];
                            }

                            $data[$managerId][$type][$dependency][$projectId] = $version;
                        }
                    }
                }
            }
        }

        return $data;
    }

}