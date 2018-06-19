<?php
namespace ApplicationComponent\Controller;

use Prontostoreus\Api\Controller\AbstractApiController;

/**
 * Applications Controller
 *
 *
 * @method \ApplicationComponent\Model\Entity\Application[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ApplicationsController extends AbstractApiController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $applications = $this->paginate($this->Applications);

        $this->set(compact('applications'));
    }

    /**
     * View method
     *
     * @param string|null $id Application id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $application = $this->Applications->get($id, [
            'contain' => []
        ]);

        $this->set('application', $application);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $application = $this->Applications->newEntity();
        if ($this->request->is('post')) {
            $application = $this->Applications->patchEntity($application, $this->request->getData());
            if ($this->Applications->save($application)) {
                $this->Flash->success(__('The application has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The application could not be saved. Please, try again.'));
        }
        $this->set(compact('application'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Application id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $application = $this->Applications->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $application = $this->Applications->patchEntity($application, $this->request->getData());
            if ($this->Applications->save($application)) {
                $this->Flash->success(__('The application has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The application could not be saved. Please, try again.'));
        }
        $this->set(compact('application'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Application id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $application = $this->Applications->get($id);
        if ($this->Applications->delete($application)) {
            $this->Flash->success(__('The application has been deleted.'));
        } else {
            $this->Flash->error(__('The application could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
