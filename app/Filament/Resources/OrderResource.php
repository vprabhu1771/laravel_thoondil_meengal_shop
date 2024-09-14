<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Models\Product;

use Filament\Forms\Get;
use Filament\Forms\Set;

use Filament\Forms\Components\Actions\Action;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->prefix('ரூ'),
                Forms\Components\Repeater::make('orderItems')
                    ->relationship()
                    // Repeatable field is live so that it will trigger the state update on each change
                    ->live(debounce:500)
                    // After adding a new row, we need to update the totals
                    ->afterStateUpdated(function (Set $set, Get $get) {
                        self::updateOrderItemAmount($set, $get);
                        self::updateOrderTotal($set, $get);
                    })
                    // After deleting a row, we need to update the totals
                    ->deleteAction(
                        fn(Action $action) => $action->after(function (Set $set, Get $get) {
                            self::updateOrderItemAmount($set, $get);
                            self::updateOrderTotal($set, $get);
                            // dd();
                        })
                    )
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->relationship('product', 'name')
                            ->live(debounce:500)
                            ->afterStateUpdated(function(Set $set, Get $get){

                                $product = Product::where('id', $get('product_id'))->first();

                                // dd($product);

                                $unit_price = $product ? $product->price : 0;
                                
                                $set('unit_price', $unit_price);
                                
                                // dd($get('../../payment_method'));                    
                                
                                self::updateOrderItemAmount($set, $get);
                                self::updateOrderTotal($set, $get);

                            })
                            ->required(),
                        Forms\Components\TextInput::make('qty')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->live(debounce:500)
                            ->afterStateUpdated(function(Set $set, Get $get){
                                self::updateOrderItemAmount($set, $get);
                                self::updateOrderTotal($set, $get);
                            }),
                        Forms\Components\TextInput::make('unit_price')
                            ->required()
                            ->numeric()
                            ->prefix('ரூ')
                            // Changed from disabled to readOnly to allow form submission
                            ->readOnly()
                            ->live(debounce:500)
                            ->afterStateUpdated(function(Set $set, Get $get){
                                // self::updateOrderItemAmount($set, $get);
                                self::updateOrderTotal($set, $get);
                            }),
                        Forms\Components\TextInput::make('sub_total')
                            ->required()
                            // Changed from disabled to readOnly to allow form submission
                            ->readOnly()
                            ->numeric()
                            ->prefix('ரூ'),
                    ])
                    ->columns(4)
                    ->columnSpanFull()
                        
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    // protected static function updateOrderItemAmount(Set $set, Get $get)
    // {
    //     $qty = (int) $get('qty');
    //     $unit_price = (int) $get('unit_price');
    //     // $discount = (int) $get('discount');

    //     $amount = $qty * $unit_price;
    //     // if ($discount > 0) {
    //     //     $amount -= ($amount * $discount / 100);
    //     // }
    //     $set('sub_total', $amount);
    // }

    // protected static function updateOrderTotal(Set $set, Get $get)
    // {
    //     $totalAmount = 0;

    //     // dd($get('../../total_amount'));
    //     // dd($get('../../orderItems'));
        
    //     // Default to an empty array if null
    //     $orderItems = $get('../../orderItems') ?? [];
        
    //     // Debugging to check the value of $orderItems
    //     // dd($orderItems);

    //     // Ensure $orderItems is an array before using array_reduce
    //     if (is_array($orderItems)) {
    //         $total_amount = array_reduce($orderItems, function($carry, $item) {
    //             return $carry + ($item['sub_total'] ?? 0);
    //         }, 0);
    //         $set('../../total_amount', $total_amount);
    //     } else {
    //         // Handle the case where $orderItems is not an array
    //         $set('../../total_amount', 0);
    //     }
    // }

    public static function updateOrderItemAmount(Set $set, Get $get): void
    {
        // Retrieve all selected order items and remove empty rows
        $selectedOrderItems = collect($get('orderItems'))->filter(fn($item) => !empty($item['product_id']) && !empty($item['qty']));
        
        // Retrieve prices for all selected products
        $prices = Product::find($selectedOrderItems->pluck('product_id'))->pluck('price', 'id');

        // Calculate subtotal for each order item
        $subtotals = $selectedOrderItems->map(function($item) use ($prices) {
            return $prices[$item['product_id']] * $item['qty'];
        });

        // Update the sub_total for each order item
        $selectedOrderItems->each(function ($item, $index) use ($set, $subtotals) {
            $set("orderItems.$index.sub_total", number_format($subtotals[$index], 2, '.', ''));
        });
    }

    public static function updateOrderTotal(Set $set, Get $get): void
    {
        // Retrieve all selected order items and remove empty rows
        $selectedOrderItems = collect($get('orderItems'))->filter(fn($item) => !empty($item['product_id']) && !empty($item['qty']));

        // Calculate total based on the subtotal for all items
        $totalAmount = $selectedOrderItems->reduce(function ($total, $item) {
            return $total + $item['sub_total'];
        }, 0);

        // Update the state with the new total value
        $set('total_amount', number_format($totalAmount, 2, '.', ''));
    }

}
