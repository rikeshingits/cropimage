<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);

        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function testadd()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            if(!empty($this->request->data['photo']['tmp_name'])) {
                if( $this->data['Institution']['logo']['type']=='image/tif' || $this->request->data['photo']['type']=='image/raw' || $this->request->data['photo']['type']=='image/png' || $this->request->data['photo']['type']=='image/gif' || $this->request->data['photo']['type']=='image/bmp' || $this->request->data['photo']['type']=='image/jpeg' || $this->request->data['photo']['type']=='image/pjpeg') {

                    $uploaded = $this->JqImgcrop->uploadImage($this->request->data['photo'], 'img/logo/', time());
                    $this->set('uploaded',$uploaded);
                    if(isset($uploaded)) {
                        unset($this->request->data['photo']);
                        $this->request->data['photo'] = $uploaded['imageName']; 
                    }
                    else {
                        unset($this->request->data['photo']);
                    }
                }
            }
            $user = $this->Users->patchEntity($user, $this->request->getData());
            // debug($user); die();
            if ($this->Users->save($user)) {
                if(isset($uploaded)) {
                    $this->Flash->success(__('The data has been saved. Crop the logo in desired size.'));
                    $this->set('page', '');
                    $user_id = $user->id;
                    $this->set('user_id', $user_id);
                    $this->render('create_thumbnail');
                }
                else {
                    $this->Flash->error(__('The user has been saved.'));
                    return $this->redirect(['action' => 'index']);
                }
            }
            else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
    }

    public function uploadimage()
    {
        if ($this->request->is('post')) {
            $id = $this->request->data['id'];
            $user = $this->Users->get($id, [
                'contain' => []
            ]);

            $uploaded = $this->upImg($this->request->data['croppedImage'], 'img/logo/', time());
            $this->set('uploaded',$uploaded);
            if(isset($uploaded)) {
                unset($this->request->data['croppedImage']);
                $this->request->data['thumbnail'] = $uploaded['imageName']; 
            }
            else {
                unset($this->request->data['croppedImage']);
            }

            $user = $this->Users->patchEntity($user, $this->request->data);
            // debug($this->request->data); die();
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
            
            // $this->set('output', $data);
            // $this->set('_serialize', 'output');
        }
    }

    public function upImg()
    {
        
    }
}
