<?php
namespace Versionable\Tests\Common\Collection;

use Versionable\Common\Collection\Collection;

use \Versionable\Common\Collection\MockComparableItem as Element;

/**
 * Test class for Collection.
 * Generated by PHPUnit on 2011-01-10 at 13:18:20.
 */
class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Collection
     */
    protected $object;
    
    protected $elements = array();

    protected function setUp()
    {
        $this->object = $this->getMockForAbstractClass('\Versionable\Common\Collection\Collection');
        
        $this->elements['alpha'] = new Element('alpha');
        $this->elements['bravo'] = new Element('bravo');
        $this->elements['charlie'] = new Element('charlie');
        $this->elements['delta'] = new Element('delta');
        $this->elements['echo'] = new Element('echo');
    }

    protected function tearDown()
    {
    }
    
    public function testClear()
    {
      $this->object->add(new \stdClass());
      
      $this->object->clear();
      
      $this->assertEmpty($this->readAttribute($this->object, 'elements'));
    }

    public function testSize()
    {
      $elements = $this->readAttribute($this->object, 'elements');
      $this->assertEquals(count($elements), $this->object->size());
    }

    public function testIsEmpty()
    {
      $elements = $this->readAttribute($this->object, 'elements');
      $this->assertEquals(empty($elements), $this->object->isEmpty());
      $this->object->add(new \stdClass());
      $elements = $this->readAttribute($this->object, 'elements');
      $this->assertEquals(empty($elements), $this->object->isEmpty());
    }
    
    public function testHashCode()
    {
      $expected = sha1('Versionable\Common\Collection\Collection' . serialize($this->readAttribute($this->object, 'elements')));
      $this->assertEquals($expected, $this->object->hashCode());
    }

    public function testContains()
    {
      $elements = $this->readAttribute($this->object, 'elements');
      $element = new \stdClass();
      $this->assertEquals(array_search($element, $elements), $this->object->contains($element));

      $this->object->add($element);
      $elements = $this->readAttribute($this->object, 'elements');
      $this->assertEquals(in_array($element, $elements), $this->object->contains($element));
    }

    public function testAdd()
    {
      $element = new \stdClass();
      $elements = $this->readAttribute($this->object, 'elements');
      $this->assertFalse(in_array($element, $elements));
      $this->object->add($element);
      $elements = $this->readAttribute($this->object, 'elements');
      $this->assertEquals(in_array($element, $elements), $this->object->contains($element));
    }
    
    public function testAddInvalid()
    {
      $this->setExpectedException('\InvalidArgumentException');
      $this->object->add(1);
    }

    public function testRemoveTrue()
    {
      $element = new \stdClass();
      $this->object->add($element);
      $this->assertTrue($this->object->remove($element));
      $elements = $this->readAttribute($this->object, 'elements');
      $this->assertFalse(in_array($element, $elements));
    }
    
    public function testRemoveFalse()
    {
      $element = new \stdClass();
      $this->assertFalse($this->object->remove(1000));
      $elements = $this->readAttribute($this->object, 'elements');
      $this->assertFalse(in_array($element, $elements));
    }

    public function testContainsAll()
    { 
      $this->object->add($this->elements['alpha']);
      $this->object->add($this->elements['bravo']);
      $this->object->add($this->elements['charlie']);
      $this->object->add($this->elements['delta']);

      $contains = $this->getMockForAbstractClass('\\Versionable\\Common\\Collection\\Collection');
      $contains->add($this->elements['bravo']);
      $contains->add($this->elements['delta']);

      $this->assertTrue($this->object->containsAll($contains));

      $contains = $this->getMockForAbstractClass('\\Versionable\\Common\\Collection\\Collection');
      $contains->add($this->elements['echo']);
      $this->assertFalse($this->object->containsAll($contains));

    }

    public function testAddAll()
    {

      $this->object->add($this->elements['bravo']);
      $this->object->add($this->elements['delta']);

      $add = $this->getMockForAbstractClass('\\Versionable\\Common\\Collection\\Collection');
      $this->object->add($this->elements['alpha']);
      $this->object->add($this->elements['charlie']);

      $this->assertTrue($this->object->addAll($add));
      $elements = $this->readAttribute($this->object, 'elements');
      $this->assertEquals($elements, array($this->elements['bravo'],$this->elements['delta'], $this->elements['alpha'], $this->elements['charlie']));
    }

    public function testRemoveAll()
    {
      $this->object->add($this->elements['alpha']);
      $this->object->add($this->elements['bravo']);
      $this->object->add($this->elements['charlie']);
      $this->object->add($this->elements['delta']);

      $remove = $this->getMockForAbstractClass('\\Versionable\\Common\\Collection\\Collection');
      $remove->add($this->elements['bravo']);
      $remove->add($this->elements['charlie']);

      $this->assertTrue($this->object->removeAll($remove));

      $elements = $this->readAttribute($this->object, 'elements');
      $this->assertEquals($elements, array($this->elements['alpha'],$this->elements['delta']));

      $this->assertTrue($this->object->removeAll());

      $elements = $this->readAttribute($this->object, 'elements');
      $this->assertEquals($elements, array());
    }

    public function testRetainAll()
    {
      $this->object->add($this->elements['alpha']);
      $this->object->add($this->elements['bravo']);
      $this->object->add($this->elements['charlie']);
      $this->object->add($this->elements['delta']);

      $retain = $this->getMockForAbstractClass('\\Versionable\\Common\\Collection\\Collection');
      $retain->add($this->elements['bravo']);
      $retain->add($this->elements['delta']);

      $this->assertTrue($this->object->retainAll($retain));

      $elements = $this->readAttribute($this->object, 'elements');
      $this->assertEquals($elements, array($this->elements['bravo'],$this->elements['delta']));

      $this->assertTrue($this->object->retainAll());

      $elements = $this->readAttribute($this->object, 'elements');
      $this->assertEquals($elements, array());
    }

    public function testToArray()
    {
      $this->object->add($this->elements['alpha']);
      $this->object->add($this->elements['bravo']);
      $this->object->add($this->elements['charlie']);
      $this->object->add($this->elements['delta']);
      
      $elements = $this->readAttribute($this->object, 'elements');
      $this->assertEquals($this->object->toArray(), $elements);
    }

    public function testGetIterator()
    {
      $this->assertEquals(new \ArrayIterator, $this->object->getIterator());
    }

    public function testIsValid()
    {
      $this->assertTrue($this->object->isValid(new Element('100')));
    }
}