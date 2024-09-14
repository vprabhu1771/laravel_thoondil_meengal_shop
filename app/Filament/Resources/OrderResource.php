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
                    ->numeric(),
                Forms\Components\Repeater::make('orderItems')
                    ->relationship()
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
                                // self::updateOrderTotal($set, $get);
                            }),
                        Forms\Components\TextInput::make('unit_price')
                            ->required()
                            ->numeric()
                            ->disabled()
                            ->live(debounce:500)
                            ->afterStateUpdated(function(Set $set, Get $get){
                                self::updateOrderItemAmount($set, $get);
                                // self::updateOrderTotal($set, $get);
                            }),
                        Forms\Components\TextInput::make('sub_total')
                            ->required()
                            ->disabled()
                            ->numeric(),
                    ])
                    ->columns(4)
                    ->columnSpanFull()
                        
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
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

    protected static function updateOrderItemAmount(Set $set, Get $get)
    {
        $qty = (int) $get('qty');
        $unit_price = (int) $get('unit_price');
        // $discount = (int) $get('discount');

        $amount = $qty * $unit_price;
        // if ($discount > 0) {
        //     $amount -= ($amount * $discount / 100);
        // }
        $set('sub_total', $amount);
    }

    protected static function updateOrderTotal(Set $set, Get $get)
    {
        $totalAmount = 0;

        // dd($get('../../total_amount'));
        // dd($get('../../orderItems'));
        
        // Default to an empty array if null
        $orderItems = $get('../../orderItems') ?? [];
        
        // Debugging to check the value of $orderItems
        // dd($orderItems);

        // Ensure $orderItems is an array before using array_reduce
        if (is_array($orderItems)) {
            $total_amount = array_reduce($orderItems, function($carry, $item) {
                return $carry + ($item['sub_total'] ?? 0);
            }, 0);
            $set('../../total_amount', $total_amount);
        } else {
            // Handle the case where $orderItems is not an array
            $set('../../total_amount', 0);
        }
    }
}
