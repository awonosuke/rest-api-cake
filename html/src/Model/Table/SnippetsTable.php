<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\I18n\FrozenTime;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Snippets Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Snippet newEmptyEntity()
 * @method \App\Model\Entity\Snippet newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Snippet[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Snippet get($primaryKey, $options = [])
 * @method \App\Model\Entity\Snippet findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Snippet patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Snippet[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Snippet|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Snippet saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Snippet[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Snippet[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Snippet[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Snippet[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SnippetsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('snippets');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        $validator
            ->scalar('content')
            ->maxLength('content', 65535)
            ->requirePresence('content', 'create')
            ->notEmptyString('content');

        $validator
            ->dateTime('expire')
            ->requirePresence('expire', 'create')
            ->notEmptyDateTime('expire');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }

    /**
     * Query builder: Find a exist snippet
     *
     * @param int $snippet_id
     * @param int $user_id
     * @return Query
     */
    public function findExistSnippet(int $snippet_id, int $user_id): Query
    {
        $snippets = TableRegistry::getTableLocator()->get('Snippets');
        return $snippets->find()->where([
            'id' => $snippet_id,
            'user_id' => $user_id,
            'expire >' => FrozenTime::now()->i18nFormat('yyyy-MM-dd HH:mm:ss')
        ]);
    }

    /**
     * Query builder: Find all exist snippet
     *
     * @param int $user_id
     * @return Query
     */
    public function findAllExistSnippet(int $user_id): Query
    {
        $snippets = TableRegistry::getTableLocator()->get('Snippets');
        return $snippets->find()->where([
            'user_id' => $user_id,
            'expire >' => FrozenTime::now()->i18nFormat('yyyy-MM-dd HH:mm:ss')
        ]);
    }

    /**
     * Query builder: Find all expired snippet
     *
     * @param int $user_id
     * @return Query
     */
    public function findAllExpiredSnippet(int $user_id): Query
    {
        $snippets = TableRegistry::getTableLocator()->get('Snippets');
        return $snippets->find()->where([
            'user_id' => $user_id,
            'expire <=' => FrozenTime::now()->i18nFormat('yyyy-MM-dd HH:mm:ss')
        ]);
    }
}
