<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Snippets Controller
 *
 * @method \App\Model\Entity\Snippet[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SnippetsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $snippets = $this->paginate($this->Snippets);

        $this->set(compact('snippets'));
    }

    /**
     * View method
     *
     * @param string|null $id Snippet id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $snippet = $this->Snippets->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('snippet'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $snippet = $this->Snippets->newEmptyEntity();
        if ($this->request->is('post')) {
            $snippet = $this->Snippets->patchEntity($snippet, $this->request->getData());
            if ($this->Snippets->save($snippet)) {
                $this->Flash->success(__('The snippet has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The snippet could not be saved. Please, try again.'));
        }
        $this->set(compact('snippet'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Snippet id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $snippet = $this->Snippets->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $snippet = $this->Snippets->patchEntity($snippet, $this->request->getData());
            if ($this->Snippets->save($snippet)) {
                $this->Flash->success(__('The snippet has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The snippet could not be saved. Please, try again.'));
        }
        $this->set(compact('snippet'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Snippet id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $snippet = $this->Snippets->get($id);
        if ($this->Snippets->delete($snippet)) {
            $this->Flash->success(__('The snippet has been deleted.'));
        } else {
            $this->Flash->error(__('The snippet could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
