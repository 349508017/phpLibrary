$db=new database\Db();
//添加一个连接
$db->addConnect([
        'driver'=>'mysqli',
        'host'=>'127.0.0.1',
        'username'=>'root',
        'password'=>'',
        'dbname'=>'blog',
        'charset'=>'utf8'
]);

//链接查询,获取多条数据
$db->where('id=1')->where('status=1')->order('id desc')->field('id,title')->limit(5)->select();

//获取一条数据
$db->where('id=1')->get();

//关联查询
$db->hasOne('table','外键','主键');

//模型用法 继承Model
class A extends database\Model{
//指定表名
	protected $table='table';
}
//模型操作指定id号
$a=new A::find(1);
//更改模型数据
$a->title=1;

//保存数据
$a->save();