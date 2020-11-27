<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListItem;
use Symfony\Component\HttpFoundation\ParameterBag;

class ListController extends Controller
{
    public function getList()
    {
        $parent = null;

        return $this->getTreeArray($parent);
    }

    public function getTreeArray($parent)
    {
        $parentItems = ListItem::where('parent_id', $parent)->orderBy('order')->get();
        $arrayBranch = array();

        foreach ($parentItems as $items) {
            $arrayBranch[] = [
                'id'            => $items->id,
                'parent_id'     => $items->parent_id,
                'order'         => $items->order,
                'name'          => $items->name,
                'items'         => $this->getTreeArray($items->id)
            ];
        }

        return $arrayBranch;
    }

    /**
     * Save or updates list.
     * If received list item has truthy ID, updates it, otherwise creates a new
     * list item
     * @param Request
     * @return Json
     */
    public function saveList(Request $request)
    {

        foreach ($request->all() as $data) {

            if ($data['id']) {

                $item = ListItem::where('id', $data['id'])->first();

                $item->update([
                    'parent_id'     => $data['parent_id'],
                    'order'         => $data['order'],
                    'name'          => $data['name']
                ]);

                foreach ($data['items'] as $sub_item) {
                    $customRequest = $this->createSaveRequest($sub_item, 'parent_id', $item->id);
                    $this->saveList($customRequest);
                }

            } else {

                $item = ListItem::create([
                    'parent_id'     => $data['parent_id'],
                    'order'         => $data['order'],
                    'name'          => $data['name']
                ]);


                foreach ($data['items'] as $sub_item) {
                    $customRequest = $this->createSaveRequest($sub_item, 'parent_id', $item->id);
                    $this->saveList($customRequest);
                }

            }
        }

        return response()->json(['message' => $data], 200);
    }

    public function createSaveRequest($sub_item, $column, $id)
    {
        $sub_item[$column] = $id;
        $request = new \Illuminate\Http\Request();
        $request->setMethod('POST');
        $parameters = new ParameterBag([$sub_item]);
        $request->merge([$sub_item]);
        $request->setJson($parameters);

        return $request;
    }

    public function deleteBranch(Request $request)
    {
        $itemToDelete = ListItem::find($request->id);
        if ($itemToDelete) {
            $itemToDelete->delete();
            $items = ListItem::where('parent_id', $request->id)->get();

            foreach ($items as $item) {
                $customRequest = $this->createDeleteRequest($item->id);
                $this->deleteBranch($customRequest);
            }
        }

        return response()->json(['message' => 'deleted: ' . $request->id], 200);
    }

    public function createDeleteRequest($id)
    {
        $request = new \Illuminate\Http\Request([
            'id' => $id
        ]);

        return $request;
    }

}
