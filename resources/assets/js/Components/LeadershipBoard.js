import React from 'react';
/*import logo from './logo.svg';
*/import './LeadershipBoard.css';
/*import Table from "./Table";
import axios from "axios";
*/
const LeadershipBoard = React.createClass({

	getInitialState() {
		return {
			leaders : []
		}
	},

	componentDidMount() {
//        this.getLeaders();
	},

    render() {    	

        let table;

        if (this.state.leaders.length > 0) {
            table = <Table leaders={this.state.leaders} callback={this.getLeaders}/>
        } else {
            table = "Wait a second while i fetch this";
        }

        return (
            <div className="App">
                <div className="App-header">
                    <img src={logo} className="App-logo" alt="logo"/>
                    <h2>A ReactJS App</h2>
                </div>
                <div className="App-intro">
                    <h3>Freecodecamp Leadership Board</h3>
                    {table}
                </div>
            </div>
        );
    },

    getLeaders(relative = 'recent'){
        return axios.get("https://fcctop100.herokuapp.com/api/fccusers/top/" + relative).then((response) => {
            this.setState({leaders : response.data});
        }).catch((error) => {
            alert("Something went wrong, check the console");
        	console.log(error);
        })
    }
});

export default LeadershipBoard;
